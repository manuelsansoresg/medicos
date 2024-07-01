<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'vapellido',
        'ttelefono',
        'tdireccion',
        'idpuesto',
        'vcedula',
        'RFC',
        'especialidad',
        'idclinica',
        'idoctora',
        'status',
        'usuario_alta',
        'vcodigodocto',
        'creador_id',
        'usuario_principal',
        
        'fecha_nacimiento',
        'codigo_paciente',
        'num_seguro',
        'foto',
        'ruta_foto',
        'sexo',
        'alergias'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getMyUserPrincipal()
    {
        $user = User::find(Auth::user()->id);
        if (Auth::user()->hasRole('administrador') || $user->usuario_principal == '') {
            return Auth::user()->id;
        }
        return $user->usuario_principal;
    }

    /**
     * get roles by user role
     */
    public static function getRoles()
    {
        $isAdmin = Auth::user()->hasRole('administrador');
        $isMedico = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        if ($isAdmin == true) {
            $puestos = config('enums.usuario_puesto');
        } elseif ($isMedico == true) {
            $puestos = config('enums.usuario_puesto_medico');
        } elseif ($isAuxiliar == true) {
            $puestos = config('enums.usuario_auxiliar');
        }

        return $puestos;
    }

    public static function getUsersByNameRol($rol)
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        
        return User::select('users.id', 'users.name')
                    ->whereHas('roles', function ($q) use($rol) {
                    $q->where('name', $rol);
                })
                ->get();
    }
    public static function getUsersByRoles($roles, $search = null, $limit = null, $isPaginate = false)
    {
        \DB::enableQueryLog(); // Enable query log

        $isAdmin = Auth::user()->hasRole('administrador');
        $users = User::
            whereHas('roles', function ($q) use($roles) {
            $q->whereIn('name', $roles);
        });
        if (!$isAdmin) {
            $usuarioPrincipal = User::getMyUserPrincipal();
            $users->where('usuario_principal', $usuarioPrincipal);
        }

        if ($search != '') {
            $users->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('vapellido', 'like', '%' . $search . '%')
                      ->orWhere('codigo_paciente', 'like', '%' . $search . '%');
            });
        }
       
        
        $limit = $limit === null ? 50 : $limit;
        if ($isPaginate === true) {
            $users = $users->paginate($limit);
        } else {
            $users = $users->get();
        }
        //dd(\DB::getQueryLog()); // Show results of log
        return $users;
    }

    public static function getMyUsersByRoles($roles, $search = null, $limit = null, $isPaginate = false)
    {
        $isAdmin = Auth::user()->hasRole('administrador');
        $myUser = User::find(Auth::user()->id);
        $users = User::
        whereHas('roles', function ($q) use($roles) {
        $q->whereIn('name', $roles);
        });

        if ($search != '') {
            $users->where('name', 'like', '%' . $search . '%');
            $users->orWhere('vapellido', 'like', '%' . $search . '%');
            $users->orWhere('codigo_paciente', 'like', '%' . $search . '%');
        }
        
        $limit = $limit === null ? 50 : $limit;
        if (!$isAdmin) {
            $users->where('usuario_principal', $myUser->usuario_principal);
        }
        if ($isPaginate === true) {
            $users = $users->paginate($limit);
        } else {
            $users = $users->get();
        }
        return $users;
    }

    public static function getUsersByRol($rol)
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        
        return User::select('users.id', 'users.name', DB::raw('MIN(consultasignado.iddoctor) as iddoctor'), DB::raw('MIN(consultasignado.idconsultorio) as idconsultorio'))
                ->join('consultasignado', 'consultasignado.iddoctor', '=', 'users.id')
                ->whereHas('roles', function ($q) use ($rol) {
                    $q->where('name', $rol);
                })
                ->groupBy('users.id', 'users.name')
                ->get();
    }

    public static function countUsersCreate($userId)
    {
        $users         = User::where('usuario_principal', $userId)->get();
        $countMedico   = 0;
        $countAuxiliar = 0;
        $countPaciente = 0;

        foreach ($users as $user) {
            if ($user->hasRole('medico')) {
                $countMedico = $countMedico + 1;
            }
            if ($user->hasRole('auxiliar')) {
                $countAuxiliar = $countAuxiliar + 1;
            }
            if ($user->hasRole('paciente')) {
                $countPaciente = $countPaciente + 1;
            }
            
        }
        $dataUser = array(
            'medico' => $countMedico,
            'auxiliar' => $countAuxiliar,
            'paciente' => $countPaciente,
        );
        return $dataUser;
    }

    public static function colorUsersCreate($users , $limit)
    {
        $percentage = $users / $limit * 100;
        $color = 'bg-success'; // Verde si falta menos de la mitad o ninguno
        if ($percentage > 0 &&  $percentage == 100) {
            $color = 'bg-danger'; // Rojo si falta más de la mitad
        } elseif ($percentage > 0 && $percentage < 50) {
            $color = 'bg-warning'; // Naranja si falta la mitad
        }

        return $color;
    }

    public static function limitAllUsers($access, $userId)
    {
        $limitDoctor   = $access->num_doctor;
        $limitAuxiliar = $access->num_auxiliar;
        $userCount     = User::countUsersCreate($userId);
        $usersDoctor   = $userCount['medico'];
        $usersAuxiliar = $userCount['auxiliar'];

        // Validar los límites
        $doctorLimitReached = $usersDoctor >= $limitDoctor;
        $auxiliarLimitReached = $usersAuxiliar >= $limitAuxiliar;

        // Si ambos límites han sido alcanzados, devolver true
        if ($doctorLimitReached && $auxiliarLimitReached) {
            return true;
        }

        // Si no, devolver false
        return false;
    }

    public static function getMyUsers($userId)
    {
        return User::where('usuario_principal', $userId)->get();
    }
    
    //*obtiene listado de tu usuario y los usuarios que te pertenecen si eres medico, si eres admin obtiene el listado de todos los usuarios
    public static function GetListUsers()
    {
        $isAdmin    = Auth::user()->hasRole('administrador');
        $isDoctor   = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $usuario_principal = User::getMyUserPrincipal();
        $roles = ['administrador', 'medico', 'auxiliar', 'secretario'];
        $users =  null;
        
        if ($isAdmin === true) {
            $users = User::getUsersByRoles($roles);
        }
        
        if ($isDoctor === true) {
            $roles = [ 'medico', 'auxiliar', 'secretario'];
            $users = User::whereHas('roles', function ($q) use($roles) {
                                $q->whereIn('name', $roles);
                            })
                            ->where(function($query) use ($usuario_principal) {
                                $query->where('id', Auth::user()->id)
                                      ->orWhere('usuario_principal', $usuario_principal);
                            })
                            ->get();
        }

        if ($isAuxiliar === true) {
            $users =  User::where('id', Auth::user()->id)
                        ->orWhere('creador_id', Auth::user()->id)
                        ->get();
        }
        
        return $users;
    }

    public static function getUsers()
    {
        $isAdmin = Auth::user()->hasRole('administrador');
        if ($isAdmin === true) { //si es admin obtener todos los usuarios que no  se le han creado sus accesos
            $users = User::where('id', '!=', Auth::user()->id)
                ->where('status', 1)
                ->get();
        } else {
            $users = User::where('id', Auth::user()->id)->where('status', 1)->get();
        }
        return $users;
    }

    public static function saveEdit($request)
    {
        $data         = $request->data;
        $user_id      = $request->user_id;
        $password     = $request->password;
        $rol          = isset( $request->rol)?  $request->rol : 'paciente';
        $current_user = Auth::user();

        if ($current_user->hasRole('medico')) {
            $data['usuario_principal'] = null;
        }
        
        if ($password != null) {
            $data['password'] = bcrypt($password);
        }

        if ($user_id == null) {
            $data['creador_id'] = Auth::user()->id;
            $user = User::create($data);
            $user->assignRole($rol);
        } else {
            $user = User::find($user_id);
            $user->fill($data);
            $user->update();

            $roles = $user->roles;
            foreach ($roles as $row_rol) {
                $user->removeRole($row_rol);
            }
            $user->assignRole($rol);
        }

        //create code user
        $codeUser = self::createCode($user->id);
        User::where('id', $user->id)->update([
            'codigo_paciente' => $codeUser
        ]);

        ClinicaUser::saveEdit($user->id, $request);
        if (!isset($data['usuario_principal'])) {
            if ($current_user->hasRole('administrador')) {
                User::where('id', $user->id)->update([
                    'usuario_principal' => $user->id
                ]);
            } elseif ($current_user->hasRole('medico')) {
                User::where('id', $user->id)->update([
                    'usuario_principal' => Auth::user()->id
                ]);
            } elseif ($current_user->hasRole('auxiliar') || $current_user->hasRole('paciente')) {
                $myUser = User::find(Auth::user()->id);
                User::where('id', $user->id)->update([
                    'usuario_principal' => $myUser->usuario_principal
                ]);
            }
        } else {
            User::where('id', $user->id)->update([
                'usuario_principal' => $data['usuario_principal']
            ]);
        }
    
        return $user;
    }

    public function createCode($userId)
    {
        $caja1 = '';
        for($i=0 ; $i<5 ; $i++)
        {   
          $abecedario ="A0BC1D2EF3G4HI5JK6LM7NO8PQ9RSTUVWXYZ";
          $des =rand(0,35);
          $has = 1;
          $letra1=substr($abecedario,$des,$has);
          @$caja1 .= $letra1;
        } 
        $caja1 = $caja1."-".$userId;
        return $caja1;
    }
}

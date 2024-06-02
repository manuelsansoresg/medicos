<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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
    public static function getUsersByRoles($roles)
    {
        return User::
            whereHas('roles', function ($q) use($roles) {
            $q->whereIn('name', $roles);
        })
        ->get();
    }
   
    public static function getUsersByRol($rol)
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        
        return User::select('users.id', 'users.name', 'iddoctor', 'idconsultorio')
                    ->join('consultasignado', 'consultasignado.iddoctor', 'users.id')
                    ->whereHas('roles', function ($q) use($rol) {
                    $q->where('name', $rol);
                })
                ->groupBy('iddoctor', 'idconsultorio')
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
        if ($percentage > 0 &&  $percentage < 25) {
            $color = 'bg-danger'; // Rojo si falta más de la mitad
        } elseif ($percentage > 0 && $percentage < 50) {
            $color = 'bg-warning'; // Naranja si falta la mitad
        }

        return $color;
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
        $myUser = User::find(Auth::user()->id);
        $users =  null;
        if ($isAdmin === true) {
            $users = User::getUsersByRoles(['administrador', 'medico', 'auxiliar', 'secretario']);
        }
        if ($isDoctor === true) {
            $users = User::where('id', Auth::user()->id)
                            ->orWhere('usuario_principal', $myUser->usuario_principal)
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

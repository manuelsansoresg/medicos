<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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

    public static function countUsersCreate($userId)
    {
        $users         = User::where('usuario_principal', $userId)->get();
        $countMedico   = 0;
        $countAuxiliar = 0;

        foreach ($users as $user) {
            if ($user->hasRole('medico')) {
                $countMedico = $countMedico + 1;
            }
            if ($user->hasRole('auxiliar')) {
                $countAuxiliar = $countAuxiliar + 1;
            }
            
        }
        $dataUser = array(
            'medico' => $countMedico,
            'auxiliar' => $countAuxiliar
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
            $users = User::all();
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
        $rol          = $request->rol;
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
        ClinicaUser::saveEdit($user->id, $request);

        if ($current_user->hasRole('administrador')) {
            User::where('id', $user->id)->update([
                'usuario_principal' => $user->id
            ]);
        } elseif ($current_user->hasRole('medico')) {
            User::where('id', $user->id)->update([
                'usuario_principal' => Auth::user()->id
            ]);
        } elseif ($current_user->hasRole('auxiliar')) {
            $myUser = User::find(Auth::user()->id);
            User::where('id', $user->id)->update([
                'usuario_principal' => $myUser->usuario_principal
            ]);
        } 
    
        return $user;
    }
}

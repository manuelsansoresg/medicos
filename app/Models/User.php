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

    public static function getUsers()
    {
        $isAdmin = Auth::user()->hasRole('administrador');
        if ($isAdmin === true) { //si es admin obtener todos los usuarios que no  se le han creado sus accesos
            $users = User::whereNotIn('id', function ($query) {
                $query->select('user_id')->from('access');
            })->where('id', '!=', Auth::user()->id)
                ->where('status', 1)
                ->get();
        } else {
            $users = User::where('id', Auth::user()->id)->where('status', 1)->get();
        }
        return $users;
    }

    public static function saveEdit($request)
    {
        $data       = $request->data;
        $user_id    = $request->user_id;
        $password   = $request->password;
        $rol        = $request->rol;

        
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
        return $user;
    }
}

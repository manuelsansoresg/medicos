<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        'segundo_apellido',
        'ttelefono',
        'tdireccion',
        'idpuesto',
        'vcedula',
        'is_cedula_valid',
        'RFC',
        'especialidad',
        'clinica',
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


    public static function validateCedula($userId, $request)
    {
        $user = User::where('id', $userId)->update([
            'is_cedula_valid' => $request->is_cedula_valid 
        ]);
        Solicitud::where([
            'user_id' => $userId,
            'estatus' => 0,
        ])->update([
            'estatus_validacion_cedula' => $request->is_cedula_valid
        ]);
        return $user;
    }
    

    public static function getIsPermissionDownload()
    {
        $getPrincipal = User::getMyUserPrincipal();
        $user = User::find($getPrincipal);
        if ($user->hasAnyPermission(['Descargar consulta', 'Descargar todos', 'Descargar estudios con imagenes']) || Auth::user()->hasRole('administrador')) {
            return true;
        }
        return false;
    }

    public static function getMyUserPrincipal()
    {
        $user = User::find(Auth::user()->id);
        if (Auth::user()->hasRole('administrador') || $user->usuario_principal == '') {
            return Auth::user()->id;
        }
        return $user->usuario_principal;
    }

    public static function getMyExpedients($expedients)
    {
        
        if (Auth::user()->hasRole('medico')) {
            if (Auth::user()->hasAnyPermission(['Descargar consulta', 'Descargar todos', 'Descargar estudios con imagenes'])) {
                return $expedients;
            }
        }

        if (Auth::user()->hasRole('administrador')) {
            return $expedients;
        }
        return null;
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
    public static function getUsersByRoles($roles, $search = null, $limit = null, $isPaginate = false, $setUserId = null)
    {
        \DB::enableQueryLog(); // Enable query log

        $isAdmin = Auth::user()->hasRole('administrador');
        $users = User::
            whereHas('roles', function ($q) use($roles) {
            $q->whereIn('name', $roles);
        });
        if ($setUserId != null) {
            $users->where('usuario_principal', $setUserId);
        }
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
        if ($access != null) {
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
        }
       

        // Si no, devolver false
        return false;
    }

    public static function savePermisionDownloadExpedient($request)
    {
        $permisosDescarga = isset($request->permisosDescarga) ? $request->permisosDescarga : 0;
        $userId           = $request->id;
        $user             = User::find($userId);
        $permitirDescarga = isset($request->permitirDescarga)? $request->permitirDescarga : null; //formulario template dinamico

        if ($permisosDescarga == 1) {
            $user->givePermissionTo('Descargar consulta');
            if ($user->hasPermissionTo('Descargar todos') == true) {
                $user->revokePermissionTo('Descargar todos');
            }
        } elseif ($permisosDescarga == 3) {
            if ($user->hasPermissionTo('Descargar consulta') == true) {
                $user->revokePermissionTo('Descargar consulta');
            }
            $user->givePermissionTo('Descargar todos');
        }elseif ($permisosDescarga == 4) {
            $user->revokePermissionTo('Descargar consulta');
            $user->revokePermissionTo('Descargar estudios con imagenes');
            $user->revokePermissionTo('Descargar todos');
           
        }
        
        else {
            if ($user->hasPermissionTo('Descargar consulta') == true) {
                $user->revokePermissionTo('Descargar consulta');
            }
            
            if ($user->hasPermissionTo('Descargar todos') == true) {
                $user->revokePermissionTo('Descargar todos');
            }
        }
        $permisosDescargaEstudios = isset($request->permisosDescargaEstudios) ? $request->permisosDescargaEstudios : 0;
        
        if ($permisosDescargaEstudios == 1) {
            $user->givePermissionTo('Descargar estudios con imagenes');
        } else {
            $user->revokePermissionTo('Descargar estudios con imagenes');
        }
        FieldConfigDownload::where('user_id', $userId)->delete();
        if ($permitirDescarga != null) {
            foreach ($permitirDescarga as  $permitirDescarga) {
                
                FieldConfigDownload::create([
                    'user_id' => $userId,
                    'idusrregistra' => Auth::user()->id,
                    'formulario_field_id' => $permitirDescarga,
                    'is_download' => 1,
                ]);
            }
        }
    }

    public static function getMyUsers($userId)
    {
        return User::where('usuario_principal', $userId)->get();
    }
    
    //*obtiene listado de tu usuario y los usuarios que te pertenecen si eres medico, si eres admin obtiene el listado de todos los usuarios
    public static function GetListUsers($paginate = null, $status = null, $setUserId = null)
    {
        $isAdmin    = Auth::user()->hasRole('administrador');
        $isDoctor   = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $usuario_principal = User::getMyUserPrincipal();
        $roles = ['administrador', 'medico', 'auxiliar', 'secretario'];
        $users =  null;
        
        if ($isAdmin === true) {
            $users = User::getUsersByRoles($roles, $setUserId);
        } else {
            if ($isDoctor === true) {
                $roles = [ 'medico', 'auxiliar', 'secretario'];
                $users = User::whereHas('roles', function ($q) use($roles) {
                                    $q->whereIn('name', $roles);
                                });
                if ($status === 0) {
                    $users->where('status', $status);
                }
                
                $users->where(function($query) use ($usuario_principal) {
                    $query->where('id', Auth::user()->id)
                          ->orWhere('usuario_principal', $usuario_principal);
                });
                                
            }
    
            if ($isAuxiliar === true) {
                $users =  User::where('id', Auth::user()->id);
                if ($status === 0) {
                    $users->where('status', $status);
                }
                $users ->orWhere('creador_id', Auth::user()->id);
                            
            }
            if ($paginate === null) {
                $users = $users->get();
            } else {
                $users = $users->paginate($paginate);
            }
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

    public static function createCode($userId)
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

    public static function getPersentClinic()
    {
        $idusrregistra = User::getMyUserPrincipal();
        return Clinica::where('idusrregistra', $idusrregistra)->where('istatus', 1)->count() > 0 ? true : false;
        
    }
    
    public static function getPersentConsult()
    {
        $getConsult = Consultorio::getAll();
        return count($getConsult) > 0 ? true : false;
        
    }
    
    public static function getPersentUser()
    {
        $getUsers = User::GetListUsers();
        $totalConsultaAsignado = true; // Inicializamos la variable como true
        foreach ($getUsers as $user) {
            $isConsultaAsignado = ConsultaAsignado::where('iddoctor', $user->id)->count();
            // Si algún usuario no tiene consultas asignadas, cambiamos la variable a false y rompemos el ciclo
            if ($isConsultaAsignado < 1) {
                $totalConsultaAsignado = false;
                break; // Rompemos el ciclo ya que no se cumple la condición
            }
        }
        return $totalConsultaAsignado;
    }

    public static function isConsultAssign($userId)
    {
        $isConsultaAsignado = ConsultaAsignado::where('iddoctor', $userId)->count();
        return $isConsultaAsignado > 0 ? true : false;
    }
    
    public static function getPercentPacient()
    {
        $getPercent = User::getUsersByRoles(['paciente']);
        return count($getPercent) > 0 ? true : false;
    }

    public static function getPorcentajeSistema()
    {
        $total         = 5;
        $idusrregistra = User::getMyUserPrincipal();
        $clinicat      = Clinica::where('idusrregistra', $idusrregistra)->count() > 0 ? 1 : 0;
        $consultorios  = count(Consultorio::getAll()) > 0 ? 1 : 0;
        $users         = count(User::GetListUsers())> 0 ? 1 : 0;
        $pacientes     = count(User::getUsersByRoles(['paciente']))> 0 ? 1 : 0;
        $templates    = count(FormularioConfiguration::getAllMyTemplates())> 0 ? 1 : 0;

        // Suma los valores que resulten en 1.
        $complete = $clinicat + $consultorios + $users + $pacientes + $templates;
        // Calcula el porcentaje.
        $percent = ($complete / $total) * 100;
        $data = array(
            'validateClinic' => $clinicat, 
            'validateCon' => $consultorios, 
            'validateUsers' => $users, 
            'validatePacient' => $pacientes, 
            'validateTemplate' => $templates, 
            'percent' => $percent, 
        );
        return $data;
    }

    public static function getUsersActive()
    {
        $userId =self::getMyUserPrincipal();
        //primero obtener si el paquete esta activo ya que si es activo debe tener 2 usuarios para crear de default
        $getTotalPaquete = Solicitud::select('id')
        ->where([
            'user_id' => $userId
        ])
        ->where('estatus', 1)
        ->where('catalog_prices_id', 1)
        ->first();
        $totalPaquete = $getTotalPaquete == null? 0 : 2;

        //revisar si se compraron usuarios extras y si estan activos
        $totalUsuariosSolicitud = Solicitud::select(DB::raw('SUM(cantidad) as total'))
        ->where([
            'user_id' => $userId
        ])
        ->where('estatus', 1)
        ->where('catalog_prices_id', 2)
        ->first();
        $totalUsuariosSolicitud = $totalUsuariosSolicitud == null ? 0 : $totalUsuariosSolicitud->total + $totalPaquete;
        $getUsers               = User::GetListUsers();
        $myUsers                = count($getUsers);
        $total                  = $totalUsuariosSolicitud - $myUsers ;
        self::updateUserFinishDate();
        Consultorio::updateConFinishDate();
        return $total;
    }

    public static function updateUserFinishDate()
    {
        // Obtener el usuario principal
        $userId = self::getMyUserPrincipal();

        // Consultar solicitudes del usuario principal
        $solicitudes = Solicitud::where('user_id', $userId)
            ->where('estatus', 1) // Solo solicitudes activas
            ->whereIn('catalog_prices_id', [1,2])
            ->get();

        // Determinar usuarios permitidos con base en solicitudes válidas
        $usuariosPermitidos = 0;
        foreach ($solicitudes as $solicitud) {
            if (Carbon::parse($solicitud->fecha_vencimiento)->isFuture()) {
                if ($solicitud->catalog_prices_id == 1) {
                    $usuariosPermitidos += 2; // 2 usuarios para catalog_prices_id = 1
                } elseif ($solicitud->catalog_prices_id == 2) {
                    $usuariosPermitidos += $solicitud->cantidad; // Cantidad definida
                }
            }
        }

        // Obtener la lista de usuarios ascendentes
        $usuarios = User::GetListUsers();

        // Actualizar el estado de los usuarios
        $contador = 0;
        foreach ($usuarios as $usuario) {
            if ($contador < $usuariosPermitidos) {
                $usuario->status = 1; // Mantener activo
                //echo 'activo'. $usuario->name.'<br>';
            } else {
                $usuario->status = 0; // Desactivar el resto
                //echo 'inactivo'. $usuario->name.'<br>';
            }
            $usuario->update();
            $contador++;
        }
    }
}

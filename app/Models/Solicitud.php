<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Solicitud extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitud_origin_id', //puede ser un paquete o algo extra como un usuario , clinica, consultorio
        'comprobante',
        'estatus', //TODO: verificar 0- pendiente 1- activo 2- caducado 3- cancelado
        'estatus_validacion_cedula',
        'cantidad',
        'user_id',
        'fecha_vencimiento',
        'fecha_activacion',
        'paciente_id',
        'precio_total',
        'source_id', //1- paquete 2- usuario 3- consultorio 4- paciente 5- clinica
        'payment_type',
        'observaciones',
        'fecha_pago'
    ];

    protected $table = 'solicitudes';

    public static function getAll($paginate = null, $search = null, $solicitud_origin_id = null)
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 
                'solicitudes.cantidad', 
                'solicitudes.estatus', 
                'solicitudes.created_at', 
                'solicitudes.updated_at', 
                'solicitudes.solicitud_origin_id',
                'solicitudes.user_id',
                'solicitudes.source_id',
                'users.name', 
                'users.vapellido',
                'fecha_vencimiento',
                'packages.nombre as package_nombre',
                'packages.precio as package_precio',
                'users_origin.name as user_origin_name',
                )
                ->leftJoin('packages', function ($join) {
                    $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                         ->where('solicitudes.source_id', '=', 1);
                })
                ->leftJoin('users as users_origin', function ($join) {
                    $join->on('users_origin.id', '=', 'solicitudes.solicitud_origin_id')
                         ->where('solicitudes.source_id', '=', 2);
                })
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->whereIn('estatus', [0,1,2, 4])
                ;
        } else {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at', 'name', 'vapellido', 'fecha_vencimiento', 'solicitud_origin_id', 'user_id'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->where('user_id', $user_id)
                ->whereIn('estatus', [0,1,2, 4]);
        }

        if ($solicitud_origin_id != '') {
            $solicitud->where('catalog_prices.id', $solicitud_origin_id);
        }

        if ($search != '') {
            $solicitud->where(function ($query) use ($search) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('vapellido', 'like', '%' . $search . '%');
            });
            
        }
        $solicitud->orderBy('solicitudes.id', 'DESC');
        return $paginate ? $solicitud->paginate($paginate) : $solicitud->get();
        
    }

    public static function getGanancias($paginate = null, $search = null, $fechaInicio, $fechaFinal)
    {
        \DB::enableQueryLog();
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'precio_total' , 'solicitudes.created_at', 'name', 'vapellido', 'fecha_vencimiento', 'porcentaje_ganancia', 'solicitud_origin_id', 'user_id', 'estatus', 'fecha_activacion'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal]) // Filtrar por rango de fechas
                ->whereIn('estatus', [1,3])
                ;
        } else {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'precio_total' , 'solicitudes.created_at', 'name', 'vapellido', 'fecha_vencimiento', 'porcentaje_ganancia', 'solicitud_origin_id', 'user_id', 'estatus', 'fecha_activacion'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->where('user_id', $user_id)
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal]) // Filtrar por rango de fechas
                ->where('estatus', '!=', 0)
                ->where('solicitud_origin_id', 4);
        }

        if ($search != '') {
            $solicitud->where(function ($query) use ($search) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('vapellido', 'like', '%' . $search . '%');
            });
            
        }
        $solicitud->orderBy('solicitudes.id', 'DESC');
        return $paginate ? $solicitud->paginate($paginate) : $solicitud->get();
        
    
    }

    public static function reset($solicitudId)
    {
        $getSolicitud = Solicitud::find($solicitudId);
        $dataSolicitud = array(
            'solicitud_origin_id' => $getSolicitud->solicitud_origin_id ,
            'estatus' => 0 ,
            'cantidad' => $getSolicitud->cantidad ,
            'user_id' => $getSolicitud->user_id ,
            'solicitud_origin_id' => $getSolicitud->id ,
        );
        $solicitud = Solicitud::create($dataSolicitud);
        Solicitud::where('id', $solicitudId)->update([
            'estatus' => 3
        ]);
        return $solicitud;
    }

    public static function getMyPackage()
    {
        $userId       = User::getMyUserPrincipal();
        $getSolicitud =  Solicitud::where('user_id', $userId)->where('source_id', 0)->orderBy('id', 'DESC')->first();
        $packages = $getSolicitud != null ? Package::find($getSolicitud->solicitud_origin_id) : null;
        return $packages;
    }

    public static function getStatusPackages()
    {
        $userId = User::getMyUserPrincipal();
        $data = [];
        
        // Get all active solicitations for the user
        $solicitudes = Solicitud::where('user_id', $userId)
            ->where('estatus', 1) // Active status
            ->orderBy('source_id', 'asc') // Paquetes primero, luego extras
            ->get();

        foreach ($solicitudes as $solicitud) {
            if ($solicitud->source_id == 0) {
                // It's a package, get items from ItemPackage
                $package = Package::find($solicitud->solicitud_origin_id);
                if ($package) {
                    foreach ($package->items as $item) {
                        $itemName = $item->catalogPrice->nombre;
                        $max = $item->max;
                        
                        if (!isset($data[$itemName])) {
                            $data[$itemName] = [
                                'max' => 0,
                                'solicitudes' => []
                            ];
                        }
                        
                        $data[$itemName]['max'] += $max;
                        $data[$itemName]['solicitudes'][] = [
                            'id' => $solicitud->id,
                            'max' => $max,
                            'is_package' => true
                        ];
                    }
                }
            } else {
                // It's an extra item, use solicitud's cantidad
                $catalogPrice = CatalogPrice::find($solicitud->solicitud_origin_id);
                if ($catalogPrice) {
                    $itemName = $catalogPrice->nombre;
                    $max = $solicitud->cantidad;
                    
                    if (!isset($data[$itemName])) {
                        $data[$itemName] = [
                            'max' => 0,
                            'solicitudes' => []
                        ];
                    }
                    
                    $data[$itemName]['max'] += $max;
                    $data[$itemName]['solicitudes'][] = [
                        'id' => $solicitud->id,
                        'max' => $max,
                        'is_package' => false
                    ];
                }
            }
        }
        return $data;
    }

    public static function getUsedStatusPackages()
    {
        $packages = self::getStatusPackages();
        
        $userId = User::getMyUserPrincipal();
        $data = [];
        if ($packages != null) {

            $getUser = User::selectRaw('COUNT(id) as total')->where('usuario_principal', $userId)->orWhere('id', $userId)->first();
            $getCon = Consultorio::selectRaw('COUNT(idconsultorios) as total')->where('idusrregistra', $userId)->first();
            $getClinic = Clinica::selectRaw('COUNT(idclinica) as total')->where('idusrregistra', $userId)->first();
            $getPacientes = VinculoPacienteUsuario::where('user_id', $userId)->count();

            $usuariosUsados = $getUser->total ?? 0;
            $consultoriosUsados = $getCon->total ?? 0;
            $clinicaUsada = $getClinic->total ?? 0;
            $pacientesUsados = $getPacientes ?? 0;
            
            $usuariosDisponibles = 0;
            $consultoriosDisponibles = 0;
            $clinicaDisponible = 0;
            $pacientesDisponibles = 0;

            $solicitudIdUsuarios = null;
            $solicitudIdConsultorios = null;
            $solicitudIdClinica = null;
            $solicitudIdPacientes = null;

            foreach ($packages as $key => $disponible) {
                $currentUsage = 0;
                $currentSolicitudId = null;
                
                // Determinar el tipo de recurso
                if ($key == 'usuario extra') {
                    $currentUsage = $usuariosUsados;
                } elseif ($key == 'consultorio extra') {
                    $currentUsage = $consultoriosUsados;
                } elseif ($key == 'clinica') {
                    $currentUsage = $clinicaUsada;
                } elseif ($key == 'paciente') {
                    $currentUsage = $pacientesUsados;
                }

                // Calcular el solicitudId correcto
                $remainingUsage = $currentUsage;
                foreach ($disponible['solicitudes'] as $solicitud) {
                    if ($remainingUsage <= 0) break;
                    
                    if ($remainingUsage <= $solicitud['max']) {
                        $currentSolicitudId = $solicitud['id'];
                        break;
                    }
                    
                    $remainingUsage -= $solicitud['max'];
                }

                // Si no se encontró un solicitudId válido, usar el último disponible
                if ($currentSolicitudId === null && !empty($disponible['solicitudes'])) {
                    $currentSolicitudId = end($disponible['solicitudes'])['id'];
                }

                // Actualizar los valores según el tipo de recurso
                if ($key == 'usuario extra') {
                    $usuariosDisponibles = $disponible['max'];
                    $solicitudIdUsuarios = $currentSolicitudId;
                } elseif ($key == 'consultorio extra') {
                    $consultoriosDisponibles = $disponible['max'];
                    $solicitudIdConsultorios = $currentSolicitudId;
                } elseif ($key == 'clinica') {
                    $clinicaDisponible = $disponible['max'];
                    $solicitudIdClinica = $currentSolicitudId;
                } elseif ($key == 'paciente') {
                    $pacientesDisponibles = $disponible['max'];
                    $solicitudIdPacientes = $currentSolicitudId;
                }
            }

            $data['totalUsuariosSistema'] = [
                'title' => 'Usuarios',
                'lbl' => "{$usuariosDisponibles}/{$usuariosUsados}",
                'isLimit' => $usuariosDisponibles == $usuariosUsados,
                'solicitudId' => $solicitudIdUsuarios
            ];

            $data['totalConsultorioExtra'] = [
                'title' => 'Consultorios',
                'lbl' => "{$consultoriosDisponibles}/{$consultoriosUsados}",
                'isLimit' => $consultoriosDisponibles == $consultoriosUsados,
                'solicitudId' => $solicitudIdConsultorios
            ];

            $data['totalClinica'] = [
                'title' => 'Clinica',
                'lbl' => "{$clinicaDisponible}/{$clinicaUsada}",
                'isLimit' => $clinicaDisponible == $clinicaUsada,
                'solicitudId' => $solicitudIdClinica
            ];

            $data['totalPacientes'] = [
                'title' => 'Pacientes',
                'lbl' => "{$pacientesDisponibles}/{$pacientesUsados}",
                'isLimit' => $pacientesDisponibles == $pacientesUsados,
                'solicitudId' => $solicitudIdPacientes
            ];
        }
        return $data;
    }

    public static function adjuntarComprobante($request, $solicitudId)
    {
        $comprobante = null;
        if ($request->hasFile('comprobante')) {
            // Validar el archivo de comprobante
            $validator = Validator::make($request->all(), [
                'comprobante' => 'mimes:jpeg,png,jpg,pdf|max:1024' // Máximo 1MB
            ]);

            // Si la validación falla, redireccionar con errores
            if ($validator->fails()) {
                
                return back()->withErrors($validator)->withInput();
            }

            // Procesar y guardar el archivo si la validación es exitosa
            if ($request->file('comprobante')->isValid()) {
                
                $archivo = $request->file('comprobante');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaDestino = public_path('comprobante');
                $archivo->move($rutaDestino, $nombreArchivo);
                $comprobante = $nombreArchivo;
            }
        }
        
        Solicitud::where('id', $solicitudId)->update([
            'payment_type' => $request->data['payment_type'],
            'observaciones' => $request->data['observaciones'],
            'fecha_pago' => $request->data['fecha_pago'],
            'comprobante' => $comprobante
        ]);
    }


    public static function getPaqueteActivo($solicitudId)
    {
        $solicitud = self::find($solicitudId);

        if (!$solicitud || !$solicitud->fecha_vencimiento) {
            return 0; // Si no se encuentra la solicitud o no tiene fecha de vencimiento, se considera caducado
        }

        $fechaVencimiento = Carbon::parse($solicitud->fecha_vencimiento);
        $fechaActual = Carbon::now();

        if ($fechaVencimiento->isPast()) {
            return 0; // Si la fecha de vencimiento ya pasó, devolver 0
        }

        return $fechaActual->diffInMonths($fechaVencimiento);
        
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $solicitudId = $request->solicitudId;

        // Verificamos si el usuario tiene el rol de administrador, médico o auxiliar.
        $isAdmin = Auth::user()->hasRole('administrador');
        $isMedico = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $isExistAcceso = $isAdmin === true ? false : true;
        $solicitud = null;
        $errorMessage = '';

        // Validación solo si el usuario no es admin.
        if ($isMedico || $isAuxiliar) {
            $user_id = User::getMyUserPrincipal();
            
            // Verificamos si el usuario ya tiene una solicitud de paquete básico pendiente.
            $getSolicitudPendiente = Solicitud::where('user_id', $user_id)
                                            ->whereIn('estatus', [0, 2])
                                            ->first();
            // Si ya hay una solicitud pendiente de activación
            if ($getSolicitudPendiente) {
                $isExistAcceso = true;
                $errorMessage = $getSolicitudPendiente->estatus == 0 ?  'Ya tienes una solicitud pendiente en espera de activación.' : 'Tienes tu acceso caducado, favor de reactivarlo en el panel principal';
            } else {
                // Verificamos si el usuario ya tiene un paquete básico activo.
                $getSolicitudBasico = Solicitud::where('user_id', $user_id)
                                            ->where('solicitud_origin_id', 1)
                                            ->where('estatus', 1)
                                            ->first();

                if ($getSolicitudBasico != null && $data['solicitud_origin_id'] == 0) {
                    $isExistAcceso = true;
                    $errorMessage = 'Solo puedes solicitar 1 paquete básico activo.';
                } elseif ($getSolicitudBasico == null && $data['solicitud_origin_id'] != 1) {
                    // Si el usuario quiere solicitar un paquete que no es el básico, pero no tiene uno básico activo.
                    $isExistAcceso = true;
                    $errorMessage = 'Para solicitar este paquete debes tener un paquete básico activo.';
                } else {
                    $isExistAcceso = false;
                }
            }
        }

        // Si la validación falla, enviamos el mensaje de error.
        
        if ($isExistAcceso == false) {
            if ($solicitudId == null) {
                $data['user_id'] = Auth::user()->id;
                $solicitud = new Solicitud($data);
                $solicitud->save();
            } else {
                $solicitud = Solicitud::where('id', $solicitudId)->update($data);
            }
             // vincular paciente con solicitud 
            if ($solicitudId == 4) { 
                SolicitudPaciente::where('solicitud_id', $solicitud->id)->delete();
                $pacientesIds = explode(',', $request->pacientes_ids);
                foreach ($pacientesIds as $pacientesId) {
                    SolicitudPaciente::create([
                        'solicitud_id' => $solicitud->id,
                        'paciente_id' => $pacientesId
                    ]);
                }
            }
        }
       
        return array('solicitud' => $solicitud, 'isReturnError' => $isExistAcceso, 'errorMessage' => $errorMessage);
    }
    
    public function package()
    {
        return $this->belongsTo(Package::class, 'solicitud_origin_id');
    }

    public function catalogPrice()
    {
        return $this->belongsTo(CatalogPrice::class, 'solicitud_origin_id');
    }
    
}

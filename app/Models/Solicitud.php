<?php

namespace App\Models;

use App\Lib\NotificationUser;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Solicitud extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitud_origin_id', //*puede ser un paquete o algo extra como un usuario , clinica, consultorio
        'comprobante',
        'estatus', //* 0-pendiente 1- activo 2- revision 3- caducado 4-renovado
        'estatus_validacion_cedula',
        'cantidad',
        'user_id',
        'fecha_vencimiento',
        'fecha_activacion',
        'paciente_id',
        'precio_total',
        'source_id', //* 0-paquete 1- cualquier relacion diferente a paquete usuario, clinica, consultorio, paciente
        'payment_type',
        'observaciones',
        'fecha_pago',
        'is_notification'
    ];

    protected $table = 'solicitudes';

    static $titleSolicitud = [
        0 => 'paquete',
        1 => 'usuario',
        2 => 'clinica',
        3 => 'consultorio',
        4 => 'paciente'
    ];

    static $statusSolicitud = [
        0 => 'pendiente',
        1 => 'activo',
        2 => 'revision',
        3 => 'caducado',
        4 => 'renovado'
    ];

    public static function getAll($paginate = null, $search = null, $solicitud_origin_id = null)
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            $solicitud = Solicitud::select(
                'solicitudes.id',
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.nombre 
                    ELSE catalog_prices.nombre 
                END as nombre_solicitud'),
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.precio 
                    ELSE catalog_prices.precio 
                END as precio'),
                'solicitudes.cantidad',
                'solicitudes.estatus',
                'solicitudes.created_at',
                'users.name',
                'users.vapellido',
                'solicitudes.fecha_vencimiento',
                'solicitudes.solicitud_origin_id',
                'solicitudes.user_id',
                'solicitudes.precio_total'
            )
            ->leftJoin('catalog_prices', function($join) {
                $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                    ->where('solicitudes.source_id', '!=', 0);
            })
            ->leftJoin('packages', function($join) {
                $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                    ->where('solicitudes.source_id', '=', 0);
            })
            ->join('users', 'users.id', '=', 'solicitudes.user_id')
            ->whereIn('solicitudes.estatus', [0, 1, 2, 3]);
            
            // Si necesitas añadir el filtro por user_id como en la consulta original:
            // ->where('solicitudes.user_id', $user_id)
        } else {
            $solicitud = Solicitud::select(
                'solicitudes.id',
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.nombre 
                    ELSE catalog_prices.nombre 
                END as nombre_solicitud'),
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.precio 
                    ELSE catalog_prices.precio 
                END as precio'),
                'solicitudes.cantidad',
                'solicitudes.estatus',
                'solicitudes.created_at',
                'users.name',
                'users.vapellido',
                'solicitudes.fecha_vencimiento',
                'solicitudes.solicitud_origin_id',
                'solicitudes.user_id',
                'solicitudes.precio_total'
            )
            ->leftJoin('catalog_prices', function($join) {
                $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                    ->where('solicitudes.source_id', '!=', 0);
            })
            ->leftJoin('packages', function($join) {
                $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                    ->where('solicitudes.source_id', '=', 0);
            })
            ->join('users', 'users.id', '=', 'solicitudes.user_id')
            ->where('solicitudes.user_id', $user_id)
            ->whereIn('solicitudes.estatus', [0, 1, 2, 3]);
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

        if (!$isAdmin) {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.nombre 
                    ELSE catalog_prices.nombre 
                END as nombre'),
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.precio 
                    ELSE catalog_prices.precio 
                END as precio'),
                'cantidad', 
                'solicitudes.estatus', 
                'precio_total', 
                'solicitudes.created_at', 
                'name', 
                'vapellido', 
                'fecha_vencimiento', 
                'porcentaje_ganancia', 
                'solicitud_origin_id', 
                'user_id', 
                'estatus', 
                'fecha_activacion'
                )
                ->leftJoin('catalog_prices', function($join) {
                    $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                        ->where('solicitudes.source_id', '!=', 0);
                })
                ->leftJoin('packages', function($join) {
                    $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                        ->where('solicitudes.source_id', '=', 0);
                })
                ->join('users', 'users.id', '=', 'solicitudes.user_id')
                ->where('user_id', $user_id)
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal])
                ->where('estatus', '!=', 3);
        } else {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.nombre 
                    ELSE catalog_prices.nombre 
                END as nombre'),
                DB::raw('CASE 
                    WHEN solicitudes.source_id = 0 THEN packages.precio 
                    ELSE catalog_prices.precio 
                END as precio'),
                'cantidad', 
                'solicitudes.estatus', 
                'precio_total', 
                'solicitudes.created_at', 
                'name', 
                'vapellido', 
                'fecha_vencimiento', 
                'porcentaje_ganancia', 
                'solicitud_origin_id', 
                'user_id', 
                'estatus', 
                'fecha_activacion'
                )
                ->leftJoin('catalog_prices', function($join) {
                    $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                        ->where('solicitudes.source_id', '!=', 0);
                })
                ->leftJoin('packages', function($join) {
                    $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                        ->where('solicitudes.source_id', '=', 0);
                })
                ->join('users', 'users.id', '=', 'solicitudes.user_id')
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal])
                ->whereIn('estatus', [1,4]);
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
        );
        $solicitud = Solicitud::create($dataSolicitud);
        Solicitud::where('id', $solicitudId)->update([
            'estatus' => 4
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
        $userId   = User::getMyUserPrincipal();
        $data     = [];
        
        if ($packages != null) {

            $getUser = User::selectRaw('COUNT(id) as total')
                ->where(function($query) use ($userId) {
                    $query->where('usuario_principal', $userId)
                          ->orWhere('id', Auth::user()->id);
                })
                ->whereHas('roles', function($query) {
                    $query->whereIn('name', ['administrador', 'medico', 'auxiliar', 'secretario']);
                })
                ->first();
            $getCon = Consultorio::selectRaw('COUNT(idconsultorios) as total')->where('idusrregistra', $userId)->first();
            $getClinic = Clinica::selectRaw('COUNT(idclinica) as total')->where('idusrregistra', $userId)->first();
            
            $getPacientes = User::where('usuario_principal', $userId)
                            ->where('is_share_profile', true)
                ->whereHas('roles', function($query) {
                    $query->where('name', 'paciente');
                })
                ->count();

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
                if ($key == 'usuario') {
                    $usuariosDisponibles = $disponible['max'];
                    $solicitudIdUsuarios = $currentSolicitudId;
                } elseif ($key == 'consultorio') {
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
                'isLimit' => $usuariosDisponibles < $usuariosUsados ? true : false,
                'solicitudId' => $solicitudIdUsuarios
            ];

            $data['totalConsultorioExtra'] = [
                'title' => 'Consultorios',
                'lbl' => "{$consultoriosDisponibles}/{$consultoriosUsados}",
                'isLimit' => $consultoriosDisponibles < $consultoriosUsados ? true : false,
                'solicitudId' => $solicitudIdConsultorios
            ];

            $data['totalClinica'] = [
                'title' => 'Clinica',
                'lbl' => "{$clinicaDisponible}/{$clinicaUsada}",
                'isLimit' => $clinicaDisponible < $clinicaUsada ? true : false,
                'solicitudId' => $solicitudIdClinica
            ];

            $data['totalPacientes'] = [
                'title' => 'Pacientes',
                'lbl' => "{$pacientesDisponibles}/{$pacientesUsados}",
                'isLimit' => $pacientesDisponibles < $pacientesUsados ? true : false,
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

    public static function getPaqueteVencidoByUser()
    {
        $userId = User::getMyUserPrincipal();
        $solicitud = Solicitud::where('user_id', $userId)->where('source_id', 0)->where('estatus', 1)->first();
        $getPaqueteVencido = self::getPaqueteActivo($solicitud->id);
        return array('solicitud' => $solicitud != null ? $solicitud : null, 'getPaqueteVencido' => $getPaqueteVencido);
    }

    public static function getStatusVencidoAllByUser()
    {
        $userId = User::getMyUserPrincipal();
        $solicitudes = Solicitud::where('user_id', $userId)->where('source_id', '!=', 0)->where('estatus', 1)->get();
        $solicitudesVencidas = [];
        foreach ($solicitudes as $solicitud) {
            $getPaqueteVencido = self::getPaqueteActivo($solicitud->id);
            $solicitud->getPaqueteVencido = $getPaqueteVencido;
            $solicitudesVencidas[] = array('solicitud' => $solicitud, 'getPaqueteVencido' => $getPaqueteVencido);
        }
        return array('solicitudesVencidas' => $solicitudesVencidas);
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
        $data              = $request->data;
        $solicitudId       = $request->solicitudId;
        $solicitudOriginId = $data['solicitud_origin_id'];

        //obtener el precio total de la solicitud'
        if($solicitudOriginId == 0)
        {
            $package = Package::find($solicitudOriginId);
            $data['precio_total'] = $package->precio * $data['cantidad'];
        } else {
            $item = CatalogPrice::find($solicitudOriginId);
            $data['precio_total'] = $item->precio * $data['cantidad'];
        }

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
                                            ->whereIn('estatus', [0,3])
                                            ->first();
            // Si ya hay una solicitud pendiente de activación
            if ($getSolicitudPendiente) {
                $isExistAcceso = true;
                $errorMessage = $getSolicitudPendiente->estatus == 0 ?  'Ya tienes una solicitud pendiente en espera de activación.' : 'Tienes tu acceso caducado, favor de reactivarlo en el panel principal';
            } else {
                // Verificamos si el usuario ya tiene un paquete básico activo.
                $getSolicitudBasico = Solicitud::where('user_id', $user_id)
                                            ->where('solicitud_origin_id', 1)
                                            ->where('estatus', 0)
                                            ->first();

                if ($getSolicitudBasico != null && $data['solicitud_origin_id'] == 0) {
                    $isExistAcceso = true;
                    $errorMessage = 'Solo puedes solicitar 1 paquete básico activo.';
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
           
             //TODO: b vincular paciente con solicitud 
            /* if ($solicitudId == 4) { 
                SolicitudPaciente::where('solicitud_id', $solicitud->id)->delete();
                $pacientesIds = explode(',', $request->pacientes_ids);
                foreach ($pacientesIds as $pacientesId) {
                    SolicitudPaciente::create([
                        'solicitud_id' => $solicitud->id,
                        'paciente_id' => $pacientesId
                    ]);
                }
            } */
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

    public static function calculatePackageUsage($available, $used) 
    {
        if ($available <= 0) {
            return 0;
        }
        
        $percentage = ($used / $available) * 100;
        
        // Ensure percentage is between 0 and 100
        return min(100, max(0, round($percentage)));
    }

    public static function getPackageUsageData($data) 
    {
        $usageData = [];
        $totalPercentage = 0;
        $itemCount = 0;
        
        foreach ($data as $key => $item) {
            if (isset($item['lbl'])) {
                list($available, $used) = explode('/', $item['lbl']);
                $percentage = self::calculatePackageUsage((int)$available, (int)$used);
                
                $usageData[$key] = [
                    'title' => $item['title'],
                    'available' => (int)$available,
                    'used' => (int)$used,
                    'percentage' => $percentage,
                    'isLimit' => $item['isLimit'] ?? false,
                    'solicitudId' => $item['solicitudId'] ?? null
                ];
                
                $totalPercentage += $percentage;
                $itemCount++;
            }
        }
        
        // Calculate average percentage of all items
        $usageData['total'] = [
            'percentage' => $itemCount > 0 ? round($totalPercentage / $itemCount) : 0
        ];
        
        return $usageData;
    }

    public static function paymentCardStore($request)
    {
        $solicitud = Solicitud::find($request->solicitud_id);
        if($solicitud != null)
        {
            if($solicitud->source_id == 0)
            {
                $package = Package::find($solicitud->solicitud_origin_id);
                $nombre = 'paquete '.$package->nombre;
                $id = $package->id;
            } else {
                $item = CatalogPrice::find($solicitud->solicitud_origin_id);
                $nombre = $item->nombre.' extra';
                $id = $item->id;
            }

            $amount = $solicitud->precio_total;
            $description = 'Pago de '.$nombre;
            $userId = User::getMyUserPrincipal();
            $validatedData = $request->validate([
                'card_token_id' => 'required|string',
            ]);
            //modificar solicitud de registro
            Solicitud::where('id', $solicitud->id)->update([
                'estatus' => 1, //activar la solicitud porque el pago es exitoso
            ]);

            $dataPayment = array(
                'card_token_id' => $validatedData['card_token_id'],
                'solicitud_id' => $solicitud->id,
                'user_id' => $userId,
                'amount' => $amount,
                'description' => $description,
            );
            $payment = Payment::savePayment($dataPayment);
            
           

            $notification = new NotificationUser();
            $notification->requestRegistration($userId, $solicitud->id);
            
            return response()->json($payment, 201);
        }
        
    }

    public static function paymentTransferStore($request)
    {
        $getSolicitud = Solicitud::find($request->solicitudId);
        $userId = User::getMyUserPrincipal();

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

                $dataSolicitud = array(
                    'comprobante' => $nombreArchivo,
                    'estatus' => 2 //en revision
                );
                $archivo->move($rutaDestino, $nombreArchivo);
                //modificar solicitud de registro
                Solicitud::where('id', $getSolicitud->id)->update($dataSolicitud);
                $notification = new NotificationUser();
                $notification->verifyPaymentReceipt($getSolicitud->id);
            }
        
        }
        
        
        

    }

}

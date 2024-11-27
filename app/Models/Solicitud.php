<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Solicitud extends Model
{
    use HasFactory;
    protected $fillable = [
        'catalog_prices_id',
        'comprobante',
        'estatus',
        'cantidad',
        'user_id',
        'fecha_vencimiento',
        'fecha_activacion',
        'paciente_id',
        'precio_total',
        'solicitud_origin_id'
    ];

    protected $table = 'solicitudes';

    public static function getAll($paginate = null, $search = null, $solicitud_origin_id = null)
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at', 'solicitudes.updated_at', 'name', 'vapellido', 'fecha_vencimiento', 'catalog_prices_id', 'user_id'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->whereIn('estatus', [0,1,2])
                ;
        } else {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at', 'name', 'vapellido', 'fecha_vencimiento', 'catalog_prices_id', 'user_id'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->where('user_id', $user_id)
                ->whereIn('estatus', [0,1,2]);
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
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus','precio_total', 'solicitudes.created_at', 'solicitudes.updated_at', 'name', 'vapellido', 'porcentaje_ganancia', 'fecha_vencimiento', 'catalog_prices_id', 'user_id', 'estatus', 'fecha_activacion'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal]) // Filtrar por rango de fechas
                ->whereIn('estatus', [1,3])
                ;
        } else {
            $solicitud =  Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'precio_total' , 'solicitudes.created_at', 'name', 'vapellido', 'fecha_vencimiento', 'porcentaje_ganancia', 'catalog_prices_id', 'user_id', 'estatus', 'fecha_activacion'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->where('user_id', $user_id)
                ->whereBetween('fecha_activacion', [$fechaInicio, $fechaFinal]) // Filtrar por rango de fechas
                ->where('estatus', '!=', 0)
                ->where('catalog_prices_id', 4);
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
            'catalog_prices_id' => $getSolicitud->catalog_prices_id ,
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

    public static function getPaqueteActivo($solicitud)
    {
        $userId[]     = $solicitud->user_id;
        $getUser      = User::find($solicitud->user_id);
        $userId[]     = $getUser->usuario_principal;
        $getSolicitud = Solicitud::where(function($query) use ($userId) {
                        foreach ($userId as $id) {
                            $query->orWhere('user_id', $id);
                        }
                    })
                    ->where('catalog_prices_id', 1)
                    ->where('estatus', 1)
                    
                    ->first();
        $price  = 0;
        $mesesRestantesParaCompletarDoce = null;
        
        if ($getSolicitud != null) {
            // Asumiendo que $fechaVencimiento contiene la fecha de vencimiento en formato 'Y-m-d'
            $fechaVencimiento = new DateTime($getSolicitud->fecha_vencimiento);
            $fechaActual = new DateTime(date('Y-m-d')); // Suponiendo que esta es la fecha del servidor
            // Verifica si la fecha de vencimiento es mayor que la fecha actual
            if ($fechaVencimiento > $fechaActual) {
                
                // Calcula la diferencia de años y meses sin considerar los días
                $anioDiff = $fechaVencimiento->format('Y') - $fechaActual->format('Y');
                $mesDiff = $fechaVencimiento->format('m') - $fechaActual->format('m');

                // Calcula el total de meses hasta la fecha de vencimiento
                $mesesTranscurridos = ($anioDiff * 12) + $mesDiff;

                // Asegura que no haya ningún mes transcurrido si el día actual es menor al día del mes de la fecha de vencimiento
                if ($fechaActual->format('d') < $fechaVencimiento->format('d') && $mesesTranscurridos > 0) {
                    $mesesTranscurridos--;
                }

                // Calcula los meses restantes para completar 12
                $mesesRestantesParaCompletarDoce = 12 - $mesesTranscurridos;
            } else {
                // Si la fecha de vencimiento ya ha pasado
                $mesesTranscurridos = 12; // Todos los meses ya se han consumido
                $mesesRestantesParaCompletarDoce = 0;
            }
            
            if ($mesesRestantesParaCompletarDoce > 0 ) {
                $getPrice = CatalogPrice::find($getSolicitud->catalog_prices_id);
                $precioPaquete = $getPrice->precio / 12;
                $price = $precioPaquete * $mesesRestantesParaCompletarDoce;
            }
            
        }

        return array('price' => $price, 'mesesRestantes' => $mesesRestantesParaCompletarDoce);
        
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
                                            ->where('estatus', 0)
                                            ->orWhere('estatus', 2)
                                            ->first();

            // Si ya hay una solicitud pendiente de activación
            if ($getSolicitudPendiente) {
                $isExistAcceso = true;
                $errorMessage = $getSolicitudPendiente->estatus == 0 ?  'Ya tienes una solicitud pendiente en espera de activación.' : 'Tienes tu acceso caducado, favor de reactivarlo en el panel principal';
            } else {
                // Verificamos si el usuario ya tiene un paquete básico activo.
                $getSolicitudBasico = Solicitud::where('user_id', $user_id)
                                            ->where('catalog_prices_id', 1)
                                            ->where('estatus', 1)
                                            ->first();

                if ($getSolicitudBasico != null && $data['catalog_prices_id'] == 1) {
                    $isExistAcceso = true;
                    $errorMessage = 'Solo puedes solicitar 1 paquete básico activo.';
                } elseif ($getSolicitudBasico == null && $data['catalog_prices_id'] != 1) {
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
            if ($data['catalog_prices_id'] == 4) { 
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

    
}

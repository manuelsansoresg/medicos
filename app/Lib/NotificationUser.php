<?php

namespace App\Lib;

use App\Mail\ActivateSystemEmail;
use App\Mail\AdjuntarComprobanteEmail;
use App\Mail\SolicitudPorCaducarEmail;
use App\Mail\SolicitudUsuarioEmail;
use App\Models\Setting;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NotificationUser
{
    public function requestRegistration($userId, $solicitudId, $paymentMethod = 'card')
    {
        $solicitud = Solicitud::find($solicitudId);
        $total = $solicitud->precio_total * $solicitud->cantidad;
        $setting = Setting::find(1);
       
        
        $user = User::find($userId);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'solicitud' => $solicitud,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Pago registrado',
            'total' => $total,
            'solicitudId' => $solicitudId,
            'paymentMethod' => $paymentMethod,
            'setting' => $setting,
        );
        Mail::to($user->email)->send(new SolicitudUsuarioEmail($data));
        
    }
    
    //este correo se envia cuando el usuario adjunta el comprobante de pago para avisarle que se verificara el pago
    public function verifyPaymentReceipt($solicitudId)
    {
        $solicitud = Solicitud::find($solicitudId);
        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Adjuntar comprobante',
        );
        
        Mail::to($user->email)->send(new AdjuntarComprobanteEmail($data));
    }
    //cuando el admin verifica que el comprobante adjuntado de pago es correcto
    public function activatesSystem($solicitudId)
    {
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
            'solicitudes.source_id'
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
        ->where('solicitudes.id', $solicitudId)
        ->first();

        
        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Activación',
            'solicitud' => $solicitud
        );
        
        Mail::to($user->email)->send(new ActivateSystemEmail($data));
    }
    
    //cuando la solicitud está próxima a caducar
    public function solicitudPorCaducarPaquete()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('medico')) {
            
            /* //obtener la vinculacion de la solicitud para saber si vencio la solicitud
            $vinvulacion = VinculacionSolicitud::where('idRel', Auth::user()->id)->first();
    
            if ($vinvulacion != null) {
                # si la solicitud esta proxima a vencer en 30 dias
                $solicitud30Dias = Solicitud::where('fecha_vencimiento', '<', now()->addDays(30))
                    ->where('estatus', '=', 1)
                    ->where('id', $vinvulacion->solicitudId)
                    ->first();
    
               
                $user = User::find($solicitud30Dias->user_id);
                
                if ($solicitud30Dias != null) {
                    $data = array(
                        'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
                        'from' => 'contacto@umbralcreepy.xyz',
                        'subject' => 'Solicitud por caducar',
                        'solicitud' => $solicitud30Dias
                    );
                    Mail::to($user->email)->send(new SolicitudPorCaducarEmail($data));
                }
                # si la solicitud ya vencio
                $solicitudVencida = Solicitud::where('fecha_vencimiento', '<', now())
                    ->where('estatus', '=', 1)
                    ->where('id', $vinvulacion->solicitudId)
                    ->first();
    
                if ($solicitudVencida != null) {
                    $user = User::find($solicitudVencida->user_id);
                    $data = array(
                        'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
                        'from' => 'contacto@umbralcreepy.xyz',
                        'subject' => 'Solicitud vencida',
                        'solicitud' => $solicitudVencida
                    );
                    Mail::to($user->email)->send(new SolicitudPorCaducarEmail($data));
                }
            } */
        }
       
       
    }

   
}
<?php

namespace App\Lib;

use App\Mail\ActivateSystemEmail;
use App\Mail\AdjuntarComprobanteEmail;
use App\Mail\PaquetePorVencerEmail;
use App\Mail\PaqueteVencidoEmail;
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
    public function paquetePorVencer($solicitudId)
    {
        $solicitud = Solicitud::where('solicitudes.id', $solicitudId)
                    ->join('packages', 'packages.id', '=', 'solicitudes.solicitud_origin_id')
                    ->first();

        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Paquete por vencer',
            'solicitud' => $solicitud
        );
        Mail::to($user->email)->send(new PaquetePorVencerEmail($data));
    }
    
    //cuando la solicitud está próxima a caducar
    public function paqueteVencido($solicitudId)
    {
        $solicitud = Solicitud::where('solicitudes.id', $solicitudId)
                    ->join('packages', 'packages.id', '=', 'solicitudes.solicitud_origin_id')
                    ->first();

        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Paquete vencido',
            'solicitud' => $solicitud
        );
        Mail::to($user->email)->send(new PaqueteVencidoEmail($data));
       
       
    }

    //cuando la solicitud extra está próxima a caducar
    public function paqueteExtraPorVencer($solicitudId)
    {
        $solicitud = Solicitud::select(
            'solicitudes.id',
            'catalog_prices.nombre',
            'solicitudes.precio_total',
            'solicitudes.fecha_vencimiento',
            'solicitudes.solicitud_origin_id',
            'solicitudes.user_id',
        )
        ->join('catalog_prices', 'catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
        ->where('solicitudes.id', $solicitudId)
        ->first();

        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Paquete por vencer',
            'solicitud' => $solicitud
        );
        Mail::to($user->email)->send(new PaquetePorVencerEmail($data));
    }
    
    //cuando la solicitud extra está vencida
    public function paqueteExtraVencido($solicitudId)
    {
        $solicitud = Solicitud::select(
            'solicitudes.id',
            'catalog_prices.nombre',
            'solicitudes.precio_total',
            'solicitudes.fecha_vencimiento',
            'solicitudes.solicitud_origin_id',
            'solicitudes.user_id',
        )
        ->where('solicitudes.id', $solicitudId)
        ->join('catalog_prices', 'catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
        ->first();

        $user = User::find($solicitud->user_id);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Paquete vencido',
            'solicitud' => $solicitud
        );
        Mail::to($user->email)->send(new PaqueteVencidoEmail($data));
       
       
    }

   
}
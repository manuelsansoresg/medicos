<?php

namespace App\Lib;

use App\Mail\ActivateSystemEmail;
use App\Mail\AdjuntarComprobanteEmail;
use App\Mail\SolicitudUsuarioEmail;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationUser
{
    public function requestRegistration($userId, $solicitudId)
    {
        $solicitud = Solicitud::find($solicitudId);
        $total = $solicitud->precio_total * $solicitud->cantidad;

       
        
        $user = User::find($userId);
        $data = array(
            'nombre' => $user->name. ' '.$user->vapellido.' '.$user->segundo_apellido,
            'solicitud' => $solicitud,
            'from' => 'contacto@umbralcreepy.xyz',
            'subject' => 'Pago registrado',
            'total' => $total,
            'solicitudId' => $solicitudId,
        );
        Mail::to($user->email)->send(new SolicitudUsuarioEmail($data));
        
    }
    
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
                            'solicitudes.user_id', 'catalog_prices.nombre', 'solicitudes.cantidad'
                            )->where('solicitudes.id', $solicitudId)
                        ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
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
    
}
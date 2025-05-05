<?php

namespace App\Http\Middleware;

use App\Lib\NotificationUser;
use App\Models\Access;
use App\Models\Notification;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user !== null) {

        //validar si el paquete esta vencido
        $isMedic = $user->hasRole(['medico', 'auxiliar', 'secretario']);
        if ($isMedic) {

            //validar si tu usuario vinculado a una solicitud ya vencio
            $vinculacionSolicitud = VinculacionSolicitud::where('user_id', $user->id)->first();
            if ($vinculacionSolicitud != null) {
                $solicitudVinculada  = Solicitud::where('id', $vinculacionSolicitud->solicitudId)->first();
                if ($solicitudVinculada != null) {
                    $getPaqueteVencido =  Solicitud::getPaqueteActivo($solicitudVinculada->id);
                    if ($getPaqueteVencido == 0) {
                        Session::put('AccesoVencido', 1);
                    }
                }
            }

            $getPaqueteVencido = Solicitud::getPaqueteVencidoByUser();

            $solicitud = $getPaqueteVencido['solicitud'] != null ? Solicitud::where('id', $getPaqueteVencido['solicitud']->id)->first() : null;
            
            if ($solicitud != null && $getPaqueteVencido['getPaqueteVencido'] == 1 && $solicitud->is_notification == false) { //faltan 1 mes para que vence el paquete y notificar al usuario
                $notification = new NotificationUser();
                $notification->paquetePorVencer($getPaqueteVencido['solicitud']->id);
                Solicitud::where('id', $getPaqueteVencido['solicitud']->id)->update(['is_notification' => true]);
                Notification::porVencer($getPaqueteVencido['solicitud']->id);
            }
            // si ya vencio el paquete y no se ha pagado, se le notifica al usuario
            if ($solicitud != null && $getPaqueteVencido['getPaqueteVencido'] == 0 && $solicitud->is_notification == false) {
                $notification = new NotificationUser();
                $notification->paqueteVencido($solicitud->id);
                Solicitud::where('id', $getPaqueteVencido['solicitud']->id)->update(['is_notification' => true]);
                Notification::solicitudVencida($solicitud->id);
            }
            
            //verificar las solicitudes extras por cencer y vencidos
            $getStatusVencido = Solicitud::getStatusVencidoAllByUser();
            $solicitudesVencidas = $getStatusVencido['solicitudesVencidas'];

            foreach ($solicitudesVencidas as $solicitudVencida) {
                $solicitud = $solicitudVencida['solicitud'] != null ? Solicitud::where('id', $solicitudVencida['solicitud']->id)->first() : null;
                if ($solicitud != null && $solicitudVencida['getPaqueteVencido'] == 1 && $solicitud->is_notification == false) { //faltan 1 mes para que vence el paquete y notificar al usuario
                    $notification = new NotificationUser();
                    $notification->paqueteExtraPorVencer($solicitud->id);
                    Solicitud::where('id', $solicitudVencida['solicitud']->id)->update(['is_notification' => true]);
                    Notification::porVencer($solicitudVencida['solicitud']->id);
                }
                if ($solicitud != null && $solicitudVencida['getPaqueteVencido'] == 0 && $solicitud->is_notification == false) {
                    $notification = new NotificationUser();
                    $notification->paqueteExtraVencido($solicitud->id);
                    Solicitud::where('id', $solicitudVencida['solicitud']->id)->update(['is_notification' => true]);
                    Notification::solicitudVencida($solicitudVencida['solicitud']->id);
                }
            }

           
            
        }

      //Session::put('typeError', 2);
    }

    return $next($request);
}



}

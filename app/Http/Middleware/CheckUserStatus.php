<?php

namespace App\Http\Middleware;

use App\Models\Access;
use App\Models\Solicitud;
use App\Models\User;
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
        $isMedic = $user->hasRole(['medico', 'auxiliar', 'secretario']);

        if ($isMedic) {
            $userId = User::getMyUserPrincipal();

            $getSolicitud = Solicitud::where([
                'solicitud_origin_id' => 1,
                'user_id' => $userId,
               
            ])->first();

            // Excluir rutas específicas de la redirección
            $excludedRoutes = [
                'SolicitudAdjuntarComprobante',
                'SolicitudDeleteImg',
            ];

            $currentRouteName = optional($request->route())->getName();

            \Log::info('Ruta actual:', ['route' => $currentRouteName]);

            // Si la ruta está excluida, continuar
            if (in_array($currentRouteName, $excludedRoutes)) {
                \Log::info('Ruta excluida detectada:', ['route' => $currentRouteName]);
                return $next($request);
            }

            // Si hay una solicitud, manejar redirección
            if ($getSolicitud !== null) {
                
                Session::put('typeError', 1);
                $isRedirect = false;

                if ($getSolicitud->estatus_validacion_cedula === null && $getSolicitud->estatus == 0) {
                    $redirectUrl = url('/admin/solicitudes/' . $getSolicitud->id . '/estatus/1'); 
                    $isRedirect = true;
                }
                 elseif ($getSolicitud->estatus_validacion_cedula === 1 && $getSolicitud->estatus == 0) {
                    $redirectUrl = url('/admin/solicitudes/' . $getSolicitud->id);
                    $isRedirect = true;
                }
                //dd($isRedirect, $getSolicitud->estatus, $userId);
                //\Log::info('Redirigiendo a:', ['url' => $redirectUrl]);

                // Evita múltiples redirecciones
                
                if ($isRedirect === true && $request->url() !== $redirectUrl) {
                    
                    return redirect($redirectUrl);
                }
            }

            // Validar si el usuario tiene estatus vencido
            if ($user->status == 0) {
                Session::put('typeError', 2);
            }
        }
    }

    return $next($request);
}



}

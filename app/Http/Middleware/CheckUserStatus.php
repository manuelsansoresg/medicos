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
        
        //validaciones acceso sistema

       
        $user = Auth::user();
        if ($user != null) {
            $validateRole = Auth::user()->hasRole(['medico', 'auxiliar', 'secretario']);

            if ($validateRole) {
                 //1- validacion si tiene activo el sistema
                $userId = User::getMyUserPrincipal();
                $getAccesoActivo = Solicitud::where(['catalog_prices_id' => 1, 'user_id' => $userId, 'estatus' => 1]);
                if ($getAccesoActivo == null) { // el paquete al acceso del sistema ya caduco
                    //enviar correo y alerta de notificación
                    Session::put('typeError', 1);
                }
                //validar si el usuario ya vencio con su estatus
                if ($user->status == 0) { 
                    Session::put('typeError', 2);
                }
                
            }
        }
        

        return $next($request);
    }
}

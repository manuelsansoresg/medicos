<?php

namespace App\Http\Middleware;

use App\Models\Access;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($user != null) {
            $isAdmin = Auth::user()->hasRole('administrador');
            //* 1-verificar si el usuario tiene rol asignado
            if (!$isAdmin) {
                $userAccess = User::getMyUserPrincipal();
                
                $access     = Access::where('user_id', $userAccess)->first();
                
                $fechaVencimiento = $access!=null ? $access->fecha_vencimiento : null;
                if ($access == null) {
                    abort(404);
                }
                if ($fechaVencimiento != null) {
                    $fechaVencimiento = \Carbon\Carbon::parse($fechaVencimiento)->startOfDay();
                    $fechaActual = \Carbon\Carbon::now()->startOfDay();
                    if ($fechaActual->greaterThan($fechaVencimiento) || $access->status == 0) {
                        abort(404);
                    }
                }
            }
        }
        if ($user && $user->status == 0) {
            //return redirect('account/suspend');
        }

        return $next($request);
    }
}

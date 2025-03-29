<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Solicitud;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; 

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        //1 verificar solicitudes a caducar y marcarlos caducados
        $rolesValidate = $user->hasRole(['medico', 'auxiliar']);
        if ($rolesValidate) {
            $userId = User::getMyUserPrincipal();
            Solicitud::where('user_id', $userId)
                        ->where('estatus', 1)
                        ->where('fecha_vencimiento', '<', Carbon::now())
                        ->update(['estatus' => 2]);
            
        }
        User::updateUserFinishDate();
        Consultorio::updateConFinishDate();
    }

    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['status' => 1]
        );
    }

}

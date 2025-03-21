<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Lib\NotificationUser;
use App\Models\CatalogPrice;
use App\Models\Solicitud;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'vapellido' => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['required', 'string', 'max:255'],
            'ttelefono' => ['required', 'int'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $rol = 'medico';
        
        $user =  User::create([
            'name' => $data['name'],
            'vapellido' => $data['vapellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'ttelefono' => $data['ttelefono'],
            'tdireccion' => $data['tdireccion'],
            'vcedula' => $data['vcedula'],
            'RFC' => $data['RFC'],
            'especialidad' => $data['especialidad'],
            'email' => $data['email'],
            'status' => 0,
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($rol);
        $codeUser = User::createCode($user->id);
        $newUser = User::where('id', $user->id)->update([
            'codigo_paciente' => $codeUser
        ]);
        
        $solicitud = Solicitud::create([
            'solicitud_origin_id' => $data['paquete-id'],
            'source_id' => 1,
            'estatus' => 0,
            'cantidad' => 1,
            'cantidad' => 1,
            'precio_total' => $data['paquete-precio'],
            'user_id' => $user->id,
        ]);
        /* $notification = new NotificationUser();
        $notification->requestRegistration($user->id, $solicitud->id);
        */
        return User::find($user->id);
    }
}

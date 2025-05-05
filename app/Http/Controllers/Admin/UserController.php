<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Access;
use App\Models\Clinica;
use App\Models\ClinicaUser;
use App\Models\Consultorio;
use App\Models\ConsultorioUser;
use App\Models\FormularioConfiguration;
use App\Models\LogSystem;
use App\Models\Notification;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users        = User::GetListUsers();
        $my_clinics   = null;
        $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
        return view('administracion.user.list', compact('users', 'my_clinics', 'getUsedStatusPackages'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id    = null;
        $user       = null;
        $clinicas   = Clinica::getAll();
        $my_clinics = ClinicaUser::where('user_id', $user_id)->get();
        $consultorios = Consultorio::getAll();
        $my_consultorios = ConsultorioUser::where('user_id', $user_id)->get();
        $puestos    = User::getRoles();
        $userAdmins = User::getUsersByNameRol('medico');
        
        return view('administracion.user.frm', compact('user', 'user_id', 'clinicas', 'my_clinics', 'puestos', 'userAdmins', 'consultorios', 'my_consultorios'));
    }

    public function permisosGet($pacienteId)
    {
        $user = User::find($pacienteId);
        $userId = $pacienteId;
        $userPrincipal  = User::getMyUserPrincipal();
        $configurations = FormularioConfiguration::where(['active' =>  1, 'user_id' => $userPrincipal])->with('fields')->first();
        $view = \View::make('administracion.user.expedients_download.content_config', compact('userId', 'user', 'configurations'))->render();
        return view('administracion.user.expedients_download.content_config', compact('userId', 'user', 'configurations'));
        return response()->json($view);
    }

    public function storeVincular(Request $request)
    {
        $solicitudId = $request->solicitud_id;
        $usuarioId = $request->usuario;

        Notification::vinculacionUsuario($usuarioId);
        return VinculacionSolicitud::addVinculacion($solicitudId, $usuarioId, 3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = $request->user_id;

        $rules = [
            'data.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ]
        ];

        if ($userId != null) {
            // For an update, add a condition to ignore the current user's email
            $rules = [
            'data.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id')
            ]
        ];
        }

        $request->validate($rules);
        User::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id    = $id;
        $user       = User::find($id);
        $clinicas   = Clinica::getAll();
        $my_clinics = ClinicaUser::where('user_id', $user_id)->get();
        $puestos    = User::getRoles();
        $userAdmins = User::getUsersByNameRol('medico');

        $consultorios = Consultorio::getAll();
        $my_consultorios = ConsultorioUser::where('user_id', $user_id)->get();

        return view('administracion.user.frm', compact('user', 'user_id', 'clinicas', 'my_clinics', 'puestos', 'userAdmins', 'consultorios', 'my_consultorios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        VinculacionSolicitud::deleteVinculacion($id);
        $user  = User::find($id)->delete();
    }

    public function showActivationForm($user_id)
    {
        $user = User::findOrFail($user_id);
        session(['userIdActivate' => $user_id]);
        return view('administracion.user.activation', compact('user'));
    }

    public function activateUser(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        
        // Handle INE front upload
        if ($request->hasFile('ine_front')) {
            $file = $request->file('ine_front');
            $filename = 'ine_frontal_' . $user_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('ine'), $filename);
            $user->ine_front = $filename;
        }

        // Handle INE back upload
        if ($request->hasFile('ine_back')) {
            $file = $request->file('ine_back');
            $filename = 'ine_reverso_' . $user_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('ine'), $filename);
            $user->ine_back = $filename;
        }

        // Update cedula validation status
        $user->is_cedula_valid = $request->input('is_cedula_valid');
        $user->save();
        
        //guardar log de quien activo la cédula
        if (Auth::user()->hasRole('administrador')) {
            $userAdminId = Auth::user()->id;
            $userNameAdmin = Auth::user()->name;
            $action = $user->is_cedula_valid == 1 ? 'valido la cédula' : 'rechazo la cédula';
            LogSystem::createLog($userAdminId, 'Validación de cédula', 'Usuario administrador '.$userNameAdmin.' '.$action.' del usuario ID-'.$user_id);
        }

        return redirect("/admin/usuarios")
                 ->with('success', 'Usuario actualizado exitosamente');
    }

    public function deleteIneImage($type)
    {
        $fieldDelete = $type == 'front' ? 'ine_front' : 'ine_back';
        $userIdFromSession = session('userIdActivate');
        $user = User::findOrFail($userIdFromSession);
        unlink(public_path('ine/' . $user->$fieldDelete));
        $user->$fieldDelete = null;
        $user->save();
        return response()->json(['success' => true]);
    }

    //seccion para obtener los consultorios de un usuario
    public function getUsuarioConsultorioClinica($user_id)
    {
        $user = User::findOrFail($user_id);
        $consultorios = ConsultorioUser::where('user_id', $user->id) ->with('consultorio')->get();
        $clinicas = ClinicaUser::where('user_id', $user->id)->with('clinica')->get();
        return response()->json(['consultorios' => $consultorios, 'clinicas' => $clinicas]);
    }
}

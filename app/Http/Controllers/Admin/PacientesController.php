<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculoPacienteUsuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::getUsersByRoles(['paciente']);
        $statusPackages = Solicitud::getUsedStatusPackages();
        return view('administracion.paciente.list', compact('users', 'statusPackages'));
    }

    public function search(Request $request)
    {
        $pacientes =  User::getUsersByRoles(['paciente'], $request->search);
        return response()->json($pacientes);
    }

    public function curp(Request $request)
    {
        $curp = $request->curp;
        $paciente = User::where('curp', $curp)->first();
        $error = true;
        if($paciente->hasRole('paciente')){
            $error = false;
        }
        return response()->json(['error' => $error, 'data' => $paciente]);
    }

    public function vincular(Request $request)
    {
        $paciente = User::find($request->user_id);
        User::vincularPaciente($paciente->id);
    }

    public function deleteVinculo(Request $request)
    {
        $paciente = User::find($request->user_id);
        User::deleteVinculo($paciente->id);
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
        $userAdmins = User::getUsersByNameRol('medico');
        
        return view('administracion.paciente.frm', compact('user', 'user_id', 'userAdmins'));
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
        $data = $request->data;
        if ($data['email'] != '') {
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
        }

        
        $user = User::saveEdit($request);
        $usuario_principal = User::getMyUserPrincipal();
        VinculoPacienteUsuario::firstOrCreate([
            'user_id' => $usuario_principal,
            'paciente_id' => $user->id
            
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user_id = $id;
        return view('administracion.paciente.info', compact('user', 'user_id'));
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
        $user       = User::find($user_id);
        $userAdmins = User::getUsersByNameRol('medico');
        return view('administracion.paciente.frm', compact('user', 'user_id', 'userAdmins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user  = User::find($id)->delete();
    }
}

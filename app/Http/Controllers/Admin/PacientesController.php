<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
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
        return view('administracion.paciente.list', compact('users'));
    }

    public function search(Request $request)
    {
        $pacientes =  User::getUsersByRoles(['paciente'], $request->search);
        return response()->json($pacientes);
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

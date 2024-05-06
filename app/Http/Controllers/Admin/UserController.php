<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Access;
use App\Models\Clinica;
use App\Models\ClinicaUser;
use App\Models\User;
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

        return view('administracion.user.list', compact('users', 'my_clinics'));
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
        $puestos    = User::getRoles();
        return view('administracion.user.frm', compact('user', 'user_id', 'clinicas', 'my_clinics', 'puestos'));
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

        return view('administracion.user.frm', compact('user', 'user_id', 'clinicas', 'my_clinics', 'puestos'));
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
        $user  = User::find($id)->delete();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultaAsignado;
use App\Models\Consultasignado;
use App\Models\Consultorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultasignadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $offices = Consultorio::getMyCon(); //consultorios
        $myUser = User::find(Auth::user()->id);
        return view('administracion.user.consultorioAsignado.frm', compact('offices', 'myUser', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $consulta = ConsultaAsignado::saveEdit($request);
        return response()->json(['data' => $consulta]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $query = ConsultaAsignado::getMyCon($id); //consultorios
        return view('administracion.user.consultorioAsignado.list', compact('query', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $idConsultorio)
    {
        $user = User::find($id);
        $offices = Consultorio::getMyCon(); //consultorios
        $myUser = User::find(Auth::user()->id);
        return view('administracion.user.consultorioAsignado.frm', compact('offices', 'myUser', 'user', 'idConsultorio'));
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
    public function destroy($id, $idConsultorio)
    {
        ConsultaAsignado::where([
            'iddoctor' => $id,
            'idconsultorio' => $idConsultorio,
        ])->delete();
    }
}

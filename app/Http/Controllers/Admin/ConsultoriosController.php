<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Consultorio::where('idconsultorios',  Session()->get('consultorio'))->get();
        return view('administracion.consultorio.list', compact('query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id       = null;
        $query    = null;
        $clinicas = Clinica::getAll();
        return view('administracion.consultorio.frm', compact('query', 'id', 'clinicas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Consultorio::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $userId = null)
    {
        $myUser = User::find(Auth::user()->id);
        $data = array(
            'idconsultorio' => $id,
            'userId' => $userId,
            'myUser' => $myUser,
        );
        $view = \View::make('administracion.consultorio.horarios', $data)->render();
        return response()->json($view);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id   = $id;
        $query      = Consultorio::find($id);
        $clinicas = Clinica::getAll(); 
        return view('administracion.consultorio.frm', compact('query', 'id', 'clinicas'));
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
        Consultorio::find($id)->delete();
    }
}

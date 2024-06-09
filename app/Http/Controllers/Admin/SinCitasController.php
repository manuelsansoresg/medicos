<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\FechaEspeciales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SinCitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = FechaEspeciales::getAll();
        return view('administracion.sin_citas.list', compact('query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id          = null;
        $query       = null;
        $consultorio = Session::get('consultorio');
        $consultorios = Consultorio::getAll();
        return view('administracion.sin_citas.frm', compact('id', 'query', 'consultorio', 'consultorios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        FechaEspeciales::saveEdit($request);
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
        $id           = $id;
        $query        = FechaEspeciales::find($id);
        $consultorio  = Session::get('consultorio');
        $consultorios = Consultorio::getAll();
        return view('administracion.sin_citas.frm', compact('id', 'query', 'consultorios', 'consultorio'));
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
        FechaEspeciales::find($id)->delete();
    }
}

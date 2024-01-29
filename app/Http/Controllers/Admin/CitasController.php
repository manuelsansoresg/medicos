<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\ConsultaAsignado;
use App\Models\Consultorio;
use App\Models\FechaEspeciales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$is_medico =  Auth::user()->hasRole('administrador');
        $fecha              = isset($_GET['fecha'])? $_GET['fecha'] : date('Y-m-d');
        $user               = Auth::user();
        $is_medico          = Auth::user()->hasRole('medico');
        $clinicas           = Clinica::getAll();
        $consultorios       = Consultorio::getAll();
        $fechasEspeciales   = FechaEspeciales::getByDate($fecha);
        $consultaAsignados   = ConsultaAsignado::getByDate($fecha);
        return view('administracion.citas.list', compact('clinicas', 'consultorios', 'is_medico', 'fechasEspeciales', 'consultaAsignados', 'fecha'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}

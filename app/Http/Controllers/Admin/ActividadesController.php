<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultaAsignado;
use App\Models\Consultorio;
use App\Models\PendienteUsr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sqlpend                = PendienteUsr::getByDay();
        $clinica                = Session::get('clinica');
        $consultorio            = Session::get('consultorio');
        $consultas              = null;
        $getAsignedConsultories = null;
        $isEmptyConsultorio     = $consultorio == null ? false : true;
        
        if ($consultorio != null) {
            $consultas              = ConsultaAsignado::getByDay();
            $getAsignedConsultories = Consultorio::getAsignedConsultories($clinica);
        }
       
        $isChangeConsultorio    = false;
        if ($getAsignedConsultories != null && $isEmptyConsultorio == false) {
            $isChangeConsultorio = true;
        }

        return view('actividades.list', compact('sqlpend', 'consultas', 'isEmptyConsultorio', 'isChangeConsultorio'));
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

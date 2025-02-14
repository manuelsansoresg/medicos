<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionRenovacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConsultoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query              = Consultorio::getAll();
        $clinica            = Session::get('clinica');
        $consultorio        = Session::get('consultorio');
        $isEmptyConsultorio = $consultorio == null ? false : true;
        $getAsignedConsultories = Consultorio::getAsignedConsultories($clinica);
        $isChangeConsultorio = false;
        if ($getAsignedConsultories != null && $isEmptyConsultorio == false) {
            $isChangeConsultorio = true;
        }
        $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
        return view('administracion.consultorio.list', compact('isEmptyConsultorio', 'query', 'isChangeConsultorio', 'getUsedStatusPackages'));
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
        VinculacionRenovacion::deleteVinculacion($id);
        Consultorio::find($id)->delete();
    }
}

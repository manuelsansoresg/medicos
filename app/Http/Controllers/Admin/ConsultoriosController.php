<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\Notification;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

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
    public function show($clinicaId, $consultorioId, $userId = null)
    {
        $myUser = User::find(Auth::user()->id);
        $data = array(
            'idconsultorio' => $consultorioId,
            'idclinica' => $clinicaId,
            'userId' => $userId,
            'myUser' => $myUser,
        );
        $view = View::make('administracion.consultorio.horarios', $data)->render();
        return response()->json($view);
    }

    public function storeVincular(Request $request)
    {
        $solicitudId = $request->solicitud_id;
        $consultorioId = $request->consultorio;

        Notification::vinculacionConsultorio($consultorioId);
        return VinculacionSolicitud::addVinculacion($solicitudId, $consultorioId, 2);
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
        VinculacionSolicitud::deleteVinculacion($id);
        Consultorio::find($id)->delete();
    }

    /**
     * Obtener lista de consultorios para el wizard
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $consultorios = Consultorio::getAll();
        return response()->json($consultorios);
    }

    /**
     * Obtener horarios de un consultorio
     *
     * @param  int  $consultorioId
     * @return \Illuminate\Http\Response
     */
    public function getHorarios($consultorioId)
    {
        // Aquí deberías obtener los horarios del consultorio
        // Por ahora retorno horarios de ejemplo
        $horarios = [
            ['id' => 1, 'hora_inicio' => '08:00', 'hora_fin' => '09:00'],
            ['id' => 2, 'hora_inicio' => '09:00', 'hora_fin' => '10:00'],
            ['id' => 3, 'hora_inicio' => '10:00', 'hora_fin' => '11:00'],
            ['id' => 4, 'hora_inicio' => '11:00', 'hora_fin' => '12:00'],
            ['id' => 5, 'hora_inicio' => '16:00', 'hora_fin' => '17:00'],
            ['id' => 6, 'hora_inicio' => '17:00', 'hora_fin' => '18:00'],
            ['id' => 7, 'hora_inicio' => '18:00', 'hora_fin' => '19:00'],
            ['id' => 8, 'hora_inicio' => '19:00', 'hora_fin' => '20:00'],
        ];
        
        return response()->json($horarios);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Citas;
use App\Models\Clinica;
use App\Models\ConsultaAsignado;
use App\Models\Consultorio;
use App\Models\FechaEspeciales;
use App\Models\Paciente;
use App\Models\User;
use App\Models\UserCita;
use DateTime;
use DateInterval;
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
        $fecha             = isset($_GET['fecha'])? $_GET['fecha'] : date('Y-m-d');
        $user              = Auth::user();
        $is_medico         = Auth::user()->hasRole('medico');
        $clinicas          = Clinica::getAll();
        $consultorios      = Consultorio::getAll();
        $fechasEspeciales  = FechaEspeciales::getByDate($fecha);
        $consultaAsignados = ConsultaAsignado::getByDate($fecha);
        $pacientes         = Paciente::getAll();
        $userAdmins        = User::getUsersByRol('medico');
        
        return view('administracion.citas.list', compact('clinicas', 'consultorios', 'is_medico', 'fechasEspeciales', 'consultaAsignados', 'fecha', 'pacientes', 'userAdmins'));
    }

    public function add(ConsultaAsignado $consultaAsignado, $hora, $fecha)
    {
        $momento          = $consultaAsignado->iturno;
        $diasemana        = $consultaAsignado->idia;
        $horas            = date('H:i', strtotime($hora));
        $fe_inicio        = date('Y-m-d', strtotime($fecha));
        $idconsultorio    = $consultaAsignado->idconsultorio;
        $lidldoctores     = $consultaAsignado->iddoctor;
        $idia     = $consultaAsignado->idia;
        $id_cita          = null;
        
        //$diastrans = 365 - $diasm;
        
        return view('administracion.citas.add', compact('momento', 'diasemana', 'horas', 'fe_inicio', 'idconsultorio', 'lidldoctores', 'id_cita', 'idia'));
    }

    public function setCita($fecha, $iddoctor)
    {
        //Buscar si existe un dia sin actividad para el consultorio
        $fechasEspeciales  = FechaEspeciales::getByDate($fecha);
        $consultaAsignados = ConsultaAsignado::getByDate($fecha, $iddoctor);
        $isBusy = count($fechasEspeciales) === 0 ? false : true;
        $data = array(
            'fechasEspeciales' => $isBusy,
            'consultaAsignados' => $consultaAsignados,
            'totalConsulta' => count($consultaAsignados),
        );
        //dd($data);
        $view = \View::make('administracion.citas.viewHours', $data)->render();
        return response()->json(['view' => $view, 'data' => $data]);
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
        UserCita::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($fecha)
    {
        $idclinica     = session()->get('clinica');
        $idconsultorio = session()->get('consultorio');

        $fechasEspeciales = FechaEspeciales::where('dfecha', '<=' , $fecha)
                            ->where('dfechafin', '>=', $fecha)
                            ->where('idclinica', $idclinica)
                            ->where('idconsultorio', $idconsultorio)
                            ->count();
        $dataResponse = array(
            'fechasEspeciales'  => $fechasEspeciales,
        );                    
        return response()->json($dataResponse);
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
        UserCita::where('id', $id)->update([
            'status' => 0
        ]);
    }
}

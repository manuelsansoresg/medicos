<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Citas;
use App\Models\Clinica;
use App\Models\ClinicaUser;
use App\Models\ConsultaAsignado;
use App\Models\Consultasignado;
use App\Models\Consultorio;
use App\Models\ConsultorioUser;
use App\Models\FechaEspeciales;
use App\Models\Paciente;
use App\Models\User;
use App\Models\UserCita;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $user              = User::find(Auth::user()->id);
        $is_medico         = Auth::user()->hasRole('medico');
        $iddoctor = null;
        if (!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('auxiliar')) {
            $iddoctor = User::getMyUserPrincipal();
        }
        
        if (Auth::user()->hasRole('auxiliar')) {
            $iddoctor = $user->id;
        }
        
        $userId            = User::getMyUserPrincipal();
        $clinicas          = Clinica::getAll();
        $getConsultorios   = ConsultorioUser::where('user_id', $userId)->get();
        $getClinicas       = ClinicaUser::where('user_id', $userId)->get();
        $fechasEspeciales  = FechaEspeciales::getByDate($fecha);
        $consultaAsignados = ConsultaAsignado::getByDate($fecha);
        $pacientes         = User::getUsersByRoles(['paciente']);
        $userAdmins        = User::getUsersByRol('medico');
        
        //$clinica            = Session::get('clinica');
        //$consultorio        = Session::get('consultorio');

        //$isEmptyConsultorio = $consultorio == null ? false : true;
        //$getAsignedConsultories = Consultorio::getAsignedConsultories($clinica);
        $isChangeConsultorio = false;
        /* if ($getAsignedConsultories != null && $isEmptyConsultorio == false) {
            $isChangeConsultorio = true;
        } */
        //dd($getAsignedConsultories);
        return view('administracion.citas.list', compact('clinicas', 'iddoctor', 'getClinicas',  'isChangeConsultorio', 'getConsultorios', 'is_medico', 'fechasEspeciales', 'consultaAsignados', 'fecha', 'pacientes', 'userAdmins'));
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

    public function setCita($fecha, $iddoctor, $idconsultorio, $idclinica)
    {
        
        //Buscar si existe un dia sin actividad para el consultorio
        $fechasEspeciales  = FechaEspeciales::getByDate($fecha, $idclinica, $idconsultorio);
        $consultaAsignados = ConsultaAsignado::getByDate($fecha, $iddoctor, $idconsultorio, $idclinica);
        $isBusy = count($fechasEspeciales) === 0 ? false : true;
        $data = array(
            'fechasEspeciales' => $isBusy,
            'consultaAsignados' => $consultaAsignados,
            'totalConsulta' => count($consultaAsignados),
        );
        $view = \View::make('administracion.citas.viewHours', $data)->render();
        return response()->json(['view' => $view, 'data' => $data]);
    }

    public function viewCitaConsultaAsignado(ConsultaAsignado $consultaAsignado)
    {
        $horarios           = ConsultaAsignado::getHoursByConsulta($consultaAsignado);
        $consultaAsignadoId = $consultaAsignado->idconsultasignado;
        $userAdmins        = User::getUsersByRol('medico');
        $iddoctor = null;
        if (!Auth::user()->hasRole('administrador')) {
            $iddoctor = User::getMyUserPrincipal();
        }
        

        return view('administracion.citas.HoraCitaUser', compact('horarios', 'consultaAsignadoId', 'iddoctor', 'userAdmins'));
    }

    public function consulta(ConsultaAsignado $consultaAsignado)
    {
        UserCita::where('consulta_asignado_id', $consultaAsignado->idconsultasignado)->update([
            'status' => 2 //En consulta
        ]);
        return response()->json('ok');
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

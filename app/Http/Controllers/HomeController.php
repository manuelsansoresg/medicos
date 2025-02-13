<?php

namespace App\Http\Controllers;

use App\Models\ClinicaUser;
use App\Models\ConsultaAsignado;
use App\Models\Consultorio;
use App\Models\PendienteUsr;
use App\Models\Solicitud;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $statusClinic  = User::getPersentConsult();
        $statusConsult = User::getPersentClinic();
        $statusUser    = User::getPersentUser();
        $statusPacient = User::getPercentPacient();
        $earrings      = PendienteUsr::getByDay(5);
        $consultorio   = Session::get('consultorio');
        $consultas     = null;
        if ($consultorio != null) {
            $consultas              = ConsultaAsignado::getByDay(5);
        }
        $getPorcentajeSistema = User::getPorcentajeSistema();
        $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
        
        return view('administracion.home', compact('statusClinic', 'statusConsult', 'statusUser', 'getUsedStatusPackages', 'statusPacient', 'earrings', 'consultas', 'getPorcentajeSistema'));
    }

    public function registroMedico()
    {
        return view('registro_medico');
    }

    public function gananciapdf()
    {
        // Fecha de inicio: primer día del mes actual
        $fechaInicio = date('Y-m-01');
        // Fecha final: último día del mes siguiente
        $fechaFinal = date('Y-m-t', strtotime('first day of +1 month'));

        $ganancias = Solicitud::getGanancias(null, null, $fechaInicio, $fechaFinal);
        $pdf = Pdf::loadView('pdf_ganancias', compact('ganancias'));
        $pdf->setPaper('A4');
        return $pdf->stream();
    
    }

    public function editProfile()
    {
        $userId = Auth::user()->id;
        return redirect('/admin/usuarios/'.$userId.'/edit');
    }

    public function clinicaYConsultorio(Request $request)
    {
        // Verifica si la variable de sesión 'clinica' existe y tiene un valor
        if ($request->session()->has('clinica') && $request->session()->get('clinica') !== '') {

            // Verifica si la variable de sesión 'consultorio' existe y tiene un valor
            if ($request->session()->has('consultorio') && $request->session()->get('consultorio') !== '') {
                // Ambas variables de sesión existen y tienen valores
                $response = [
                    'status' => 200,
                    'message' => 'Las variables de sesión "clinica" y "consultorio" existen y tienen valores.'
                ];
            } else {
                // La variable de sesión 'consultorio' no existe o no tiene valor
                $response = [
                    'status' => 600,
                    'message' => 'La variable de sesión "consultorio" no existe o no tiene valor.'
                ];
            }
        } else {
            // La variable de sesión 'clinica' no existe o no tiene valor
            $response = [
                'status' => 500,
                'message' => 'La variable de sesión "clinica" no existe o no tiene valor.'
            ];
        }

        // Devuelve el resultado en formato JSON
        return response()->json($response);
    }

    public function viewClinicaYConsultorio()
    {
        $my_clinics = ClinicaUser::myClinics();
        return view('CinicaYConsultorio', compact('my_clinics'));
    }
}

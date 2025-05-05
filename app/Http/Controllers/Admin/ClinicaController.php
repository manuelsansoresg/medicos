<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\Notification;
use App\Models\Solicitud;
use App\Models\VinculacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clinicas = Clinica::getAll();
        $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
        return view('administracion.clinica.list', compact('clinicas', 'getUsedStatusPackages'));
    }

    

    public function consultorioGet(Clinica $clinica)
    {
        $consultorios = Consultorio::getAsignedConsultories($clinica->idclinica);
        return response()->json($consultorios);
    }
    
    public function myConfiguration(Request $request)
    {
        $clinica = $request->session()->has('clinica');
        $consultorio = $request->session()->get('consultorio');
    }

    public function setClinicaConsultorio(Request $request)
    {
        // Obtener los valores de consultorio y clinica del request
        $consultorio = $request->input('consultorio');
        $clinica = $request->input('clinica');

        // Asignar valores a las variables de sesión
        Session::put('clinica', $clinica);
        Session::put('consultorio', $consultorio);
    }

    public function getClinicaConsultorio()
    {
        $clinica = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        return response()->json([
            'clinica' => $clinica,
            'consultorio' => $consultorio,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clinica_id   = null;
        $clinica      = null;
        return view('administracion.clinica.frm', compact('clinica', 'clinica_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Clinica::saveEdit($request);
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
        $clinica_id   = $id;
        $clinica      = Clinica::find($id);
        
        return view('administracion.clinica.frm', compact('clinica', 'clinica_id'));
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

    public function storeVincular(Request $request)
    {
        $solicitudId = $request->solicitud_id;
        $clinicaId = $request->clinica;

        Notification::vinculacionClinica($clinicaId);
        return VinculacionSolicitud::addVinculacion($solicitudId, $clinicaId, 1);

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
        Clinica::find($id)->delete();
    }

    public function mount($limit)
    {
        $this->limit = $limit;
        $this->my_clinics = ClinicaUser::myClinics();
        $this->my_consultories = ConsultorioUser::myConsultories();
        $this->clinica = session('clinica', '');
        $this->consultorio = session('consultorio', '');
    }

    public function updatedClinica($value)
    {
        session(['clinica' => $value]);
    }

    public function updatedConsultorio($value)
    {
        session(['consultorio' => $value]);
    }
}

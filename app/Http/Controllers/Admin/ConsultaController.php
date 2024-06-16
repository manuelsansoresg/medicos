<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\User;
use App\Models\UserCita;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        Consulta::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consulta = Consulta::find($id);
        return response()->json($consulta);
    }

    public function registroConsulta($userCitaId, $consultaAsignadoId)
    {
        $userCita       = UserCita::find($userCitaId);
        $paciente       = User::find($userCita->paciente_id);
        $ultimaConsulta = Consulta::where('paciente_id', $paciente->id)->orderBy('created_at', 'DESC')->first();
        $consultas      = Consulta::getByPaciente($paciente->id);
        $isExpedient    = false;
        return view('administracion.consulta.form', compact('consultaAsignadoId', 'paciente', 'ultimaConsulta', 'consultas', 'userCitaId', 'isExpedient'));
    }

    public function recetaPdf(Consulta $consulta, $type)
    {
        $getUserMedic = User::find($consulta->idusrregistra);
        $getMedico    = User::find($getUserMedic->usuario_principal);
        $medico       = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente     = User::find($consulta->paciente_id);
        
        $data         = array(
            'consulta' => $consulta,
            'medico'   => $medico,
            'paciente' => $paciente
        );
        if ($type == 'consulta') {
            $pdf = Pdf::loadView('administracion.consulta.consulta', $data);
        } else {
            $pdf = Pdf::loadView('administracion.consulta.receta', $data);
        }

        $pdf->setPaper('A4');

        return $pdf->stream();
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
        Consulta::where('id', $id)->delete();
    }
}

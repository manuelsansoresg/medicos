<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\FormularioConfiguration;
use App\Models\FormularioEntry;
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

        $entryId = $id;
        $entry = FormularioEntry::with('fields.field')->findOrFail($entryId);
        return view('formulario_configurations.show_saved', compact('entry'));
        //return \View::make('formulario_configurations.show_saved', compact('configuration', 'consultaId', 'userCitaId', 'paciente'))->render();
        
    }

    public function registroConsulta($userCitaId, $consultaId)
    {
        $userCita       = UserCita::find($userCitaId);
        $paciente       = User::find($userCita->paciente_id);
        $ultimaConsulta = Consulta::where('paciente_id', $paciente->id)->orderBy('created_at', 'DESC')->first();
        //$consultas      = Consulta::getByPaciente($paciente->id);
        $isExpedient    = false;
        $myTemplates    = FormularioConfiguration::getMyTemplates();
        $totalTemplates = 0;
        if (count($myTemplates) > 1) {
            $configuration = null;
            $totalTemplates = count($myTemplates);
        } elseif (count($myTemplates) == 1) {
            $myTemplates = $myTemplates[0];
            $configuration = FormularioConfiguration::with('fields')->findOrFail($myTemplates->id);
            $totalTemplates = 1;
        }
        $consultas = FormularioEntry::where('consulta_id', $consultaId)->get();
        return view('administracion.consulta.form', compact('consultaId', 'paciente', 'ultimaConsulta', 'consultas', 'userCitaId', 'isExpedient', 'myTemplates', 'totalTemplates'));
    }

    public function recetaPdf($entryId, $type)
    {
        $entry = FormularioEntry::with('fields.field')->findOrFail($entryId);
        $getUserMedic = User::find($entry->idusrregistra);
        $getMedico    = User::find($getUserMedic->usuario_principal);
        $medico       = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente     = User::find($entry->paciente_id);
        
        $data         = array(
            'entry' => $entry,
            'medico'   => $medico,
            'paciente' => $paciente
        );
        $pdf = Pdf::loadView('administracion.consulta.consulta', $data);
        /* if ($type == 'consulta') {
            $pdf = Pdf::loadView('administracion.consulta.consulta', $data);
        } else {
            $pdf = Pdf::loadView('administracion.consulta.receta', $data);
        } */

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
        FormularioEntry::where('id', $id)->delete();
    }
}

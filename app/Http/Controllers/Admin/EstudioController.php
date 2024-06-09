<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Estudio;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EstudioController extends Controller
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

    public function recetaPdf(Estudio $estudio)
    {
        $getUserMedic = User::find($estudio->idusrregistra);
        $getMedico    = User::find($getUserMedic->usuario_principal);
        $medico       = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente     = User::find($estudio->paciente_id);
        $ultimaConsulta = Consulta::getLastPesoEstaturta($paciente->id);

        $data         = array(
            'estudio' => $estudio,
            'medico'   => $medico,
            'paciente' => $paciente,
            'peso' => $ultimaConsulta['peso'],
            'estatura' => $ultimaConsulta['estatura'],
        );

        $pdf = Pdf::loadView('administracion.consulta.estudio', $data);
        $pdf->setPaper('A4');

        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {
        Estudio::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudio = Estudio::find($id);
        return response()->json($estudio);
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
        Estudio::where('id', $id)->delete();
    }
}

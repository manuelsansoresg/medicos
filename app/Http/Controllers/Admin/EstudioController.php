<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Estudio;
use App\Models\EstudioImagen;
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

    public function saveEstudioPdf($estudioId, $isPermission)
    {
        $estudio        = Estudio::find($estudioId);
        $getUserMedic   = User::find($estudio->idusrregistra);
        $getMedico      = User::find($getUserMedic->usuario_principal);
        $medico         = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente       = User::find($estudio->paciente_id);
        $ultimaConsulta = Consulta::getLastPesoEstaturta($paciente->id);
        $images         = EstudioImagen::where('estudio_id', $estudio->id)->get();
        $prefijo = $isPermission == false ? 's_i-' : null;
        $nameExpedient = $prefijo.$paciente->id.'-'.$paciente->name.' '.$paciente->vapellido.'.pdf';
        $userPermisions = User::find($paciente->usuario_principal);

        $data         = array(
            'estudio'    => $estudio,
            'medico'     => $medico,
            'paciente'   => $paciente,
            'images'     => $images,
            'peso'       => $ultimaConsulta['peso'],
            'estatura'   => $ultimaConsulta['estatura'],
            'isPermission' => $isPermission,
            'userPermisions' => $userPermisions,
        );

        $pdf = Pdf::loadView('administracion.consulta.estudio', $data);
        $pdf->setPaper('A4');

        return $pdf->save('estudios/'.$nameExpedient);
    }

    public function estudioPdf(Estudio $estudio)
    {
        $getUserMedic   = User::find($estudio->idusrregistra);
        $getMedico      = User::find($getUserMedic->usuario_principal);
        $medico         = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente       = User::find($estudio->paciente_id);
        $ultimaConsulta = Consulta::getLastPesoEstaturta($paciente->id);
        $images         = EstudioImagen::where('estudio_id', $estudio->id)->get();
        
        $userPermisions = User::find($paciente->usuario_principal);

        $data         = array(
            'estudio'        => $estudio,
            'medico'         => $medico,
            'paciente'       => $paciente,
            'images'         => $images,
            'userPermisions' => $userPermisions,
            'peso'           => $ultimaConsulta['peso'],
            'estatura'       => $ultimaConsulta['estatura'],
        );

        $pdf = Pdf::loadView('administracion.consulta.estudio', $data);
        $pdf->setPaper('A4');
        
        //return $pdf->save('expedientes/'.$nameExpedient);
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
        $estudio = Estudio::saveEdit($request);
        self::saveEstudioPdf($estudio->id, false);
        self::saveEstudioPdf($estudio->id, true);
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

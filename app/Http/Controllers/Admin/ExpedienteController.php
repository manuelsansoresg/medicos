<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administracion.expedientes.list');
    }

    public function downloadExpedient(Request $request)
    {
        // Fecha y hora actuales para el nombre del ZIP
        $zipFileName = 'expedientes_' . Carbon::now()->format('Ymd_His') . '.zip';
        $zipFilePath = public_path($zipFileName);  // El archivo ZIP se creará en la carpeta public

        // Crear instancia de ZipArchive
        $zip = new ZipArchive;
        
        // Abrir o crear el ZIP
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {

            $expedients = $request->expedients;
            
            // Recorrer cada expediente seleccionado
            foreach ($expedients as $expedient) {
                $paciente = User::find($expedient);
                $nameStudy = null;
                $nameExpedient = null;
                //si es admin y si es descargar todos
                if (Auth::user()->hasPermissionTo('Descargar todos') || Auth::user()->hasRole('administrador')) {
                    $nameExpedient = public_path('expedientes/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                    if (Auth::user()->hasRole('administrador') || Auth::user()->hasPermissionTo('Descargar estudios con imagenes')) { //descargar estudios con imagenes
                        $nameStudy = public_path('estudios/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                    } else {
                        $nameStudy = public_path('estudios/s_i-' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                    }
                } elseif (!Auth::user()->hasPermissionTo('Descargar todos') && Auth::user()->hasPermissionTo('Descargar consulta')) { //descargar solo consulta
                    $nameExpedient = public_path('expedientes/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                } elseif (!Auth::user()->hasPermissionTo('Descargar todos') && !Auth::user()->hasPermissionTo('Descargar consulta')) { //descargar solo estudios
                    if (Auth::user()->hasRole('administrador') || Auth::user()->hasPermissionTo('Descargar estudios con imagenes')) { //descargar estudios con imagenes
                        $nameStudy = public_path('estudios/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                    } else {
                        $nameStudy = public_path('estudios/s_i-' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                    }
                }
                
                
                
                // Verificar si el archivo existe antes de añadir al ZIP
                if (file_exists($nameExpedient)) {
                    // Agregar el archivo al ZIP
                    $zip->addFile($nameExpedient, 'expedientes/' . basename($nameExpedient));
                }

                if (file_exists($nameStudy)) {
                    // Agregar el archivo al ZIP
                    $zip->addFile($nameStudy, 'estudios/' . basename($nameStudy));
                }
            }
            
            // Cerrar el ZIP después de agregar los archivos
            $zip->close();

            // Forzar la descarga del ZIP
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paciente = User::find($id);
        if (!$paciente->hasRole('paciente')) {
            return abort(404);
        }
        $isExpedient = true;
        $ultimaConsulta = Consulta::where('paciente_id', $paciente->id)->orderBy('created_at', 'DESC')->first();
        return view('administracion.expedientes.show', compact('paciente', 'ultimaConsulta', 'isExpedient'));
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
        //
    }
}

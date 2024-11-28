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

        $zipFileName = 'expedientes_' . Carbon::now()->format('Ymd_His') . '.zip';
        $zipFilePath = public_path($zipFileName);  // El archivo ZIP se creará en la carpeta public

        $zip = new ZipArchive;

        // Abrir o crear el ZIP
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {

            $expedients = $request->expedients;
            $filesAdded = 0; // Contador para los archivos agregados al ZIP

            foreach ($expedients as $expedient) {
                $paciente = User::find($expedient);

                // Construir rutas de posibles archivos
                $expedientPath = public_path('expedientes/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');
                $studyPath = Auth::user()->hasRole('administrador') || Auth::user()->hasPermissionTo('Descargar estudios con imagenes')
                    ? public_path('estudios/' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf')
                    : public_path('estudios/s_i-' . $paciente->id . '-' . $paciente->name . ' ' . $paciente->vapellido . '.pdf');

                // Validar archivos antes de intentar agregarlos
                if (file_exists($expedientPath)) {
                    $zip->addFile($expedientPath, 'expedientes/' . basename($expedientPath));
                    $filesAdded++;
                }

                if (file_exists($studyPath)) {
                    $zip->addFile($studyPath, 'estudios/' . basename($studyPath));
                    $filesAdded++;
                }
            }

            $zip->close();

            // Verificar si se añadieron archivos al ZIP
            if ($filesAdded > 0) {
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                // Eliminar el archivo ZIP vacío
                unlink($zipFilePath);
                return response()->json(['error' => 'No se encontraron archivos válidos para agregar al ZIP.'], 404);
            }
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

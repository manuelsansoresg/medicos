<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Estudio;
use App\Models\FormularioEntry;
use App\Models\User;
use App\Models\UserCita;
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

    public function descargarArchivos(Request $request)
    {
        $user = auth()->user();

        $permissionConsultaEstudios     = $user->hasPermissionTo('Descargar consulta') == 1 ? 1: 0 ; // 1 = solo consulta  0 = solo estudios
        $permisionDownloadAll   = $user->hasPermissionTo('Descargar todos')    == 1 ? 1: 0 ; // 1= si 0 = no
        $permissionDownloadAny     = $user->hasPermissionTo('Descargar ninguno') == 1 ? 1: 0 ; // 1= si 0 = no
        $permisionDownloadStudyImages = $user->hasPermissionTo('Descargar estudios con imagenes')    == 1 ? 1 : 0 ;

        $pacienteIds = $request->expedients;
        if (!$pacienteIds || !is_array($pacienteIds)) {
            return response()->json(['error' => 'No hay expedientes seleccionados.'], 400);
        }

        $zip = new \ZipArchive();
        $user = auth()->user();
        $fecha = \Carbon\Carbon::now()->format('Ymd_His');
        // Crear el ZIP en la carpeta public/expedientes
        $zipDir = public_path('expedientes');
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0777, true);
        }
        $zipFileName = 'expedientes-' . $user->name . '-' . $fecha . '.zip';
        $zipFilePath = $zipDir . DIRECTORY_SEPARATOR . $zipFileName;

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) !== TRUE) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }

        foreach ($pacienteIds as $paciente_id) {
            $paciente =User::find($paciente_id);
            if (!$paciente) continue;

            // 1. Consultas: UserCita y FormularioEntry
            if ($permissionConsultaEstudios == 1 || $permisionDownloadAll == 1) {
                $citas = UserCita::where('paciente_id', $paciente_id)->get();
                foreach ($citas as $cita) {
                    $formularios = FormularioEntry::where('user_cita_id', $cita->id)->get();
                    foreach ($formularios as $formulario) {
                        $archivoPath = public_path('expedientes/' . basename($formulario->archivo));
                        if ($formulario->archivo && file_exists($archivoPath)) {
                            $zip->addFile($archivoPath, 'consultas/' . basename($formulario->archivo));
                        }
                    }
                }
            }

            // 2. Estudios: Estudio
            if ($permissionConsultaEstudios == 0 || $permisionDownloadAll == 1) {
                $estudios = Estudio::where('paciente_id', $paciente_id)->get();
                
                foreach ($estudios as $estudio) {
                    $archivoPath = public_path('estudios/' . basename($estudio->archivo));
                    if ($estudio->archivo && file_exists($archivoPath)) {
                        //validar permisos si el pdf sera con o sin imagenes
                        if ($permisionDownloadStudyImages == 1) {
                            $zip->addFile($archivoPath, 'estudios/' . basename($estudio->archivo));
                        } else {
                            $zip->addFile($archivoPath, 'estudios/s_i-' . basename($estudio->archivo));
                        }
                    }
                }
            }
        }

        $zip->close();

        if (file_exists($zipFilePath)) {
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'No se encontraron archivos para descargar.'], 404);
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

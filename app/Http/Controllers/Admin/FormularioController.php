<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormularioConfiguration;
use App\Models\FormularioEntry;
use App\Models\FormularioEntryField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FormularioController extends Controller
{
    public function show($id, $consultaId)
    {
        $configuration = FormularioConfiguration::with('fields')->findOrFail($id);
        return view('formularios.show', compact('configuration', 'consultaId'));
    }

    public function saveExpedient($entryId)
    {
        $entry = FormularioEntry::with('fields.field')->findOrFail($entryId);
        $getForms = FormularioEntry::getFieldByEntryId($entryId);
        $getUserMedic = User::find($entry->idusrregistra);
        $getMedico    = User::find($getUserMedic->usuario_principal);
        $medico       = $getMedico == null ? $getUserMedic : $getMedico;
        $paciente     = User::find($entry->paciente_id);

        $nameExpedient = $paciente->id.'-'.$paciente->name.' '.$paciente->vapellido.'.pdf';
        
        $data         = array(
            'entry' => $entry,
            'getForms' => $getForms,
            'medico'   => $medico,
            'paciente' => $paciente
        );
        $pdf = Pdf::loadView('administracion.consulta.consulta', $data);
        $pdf->setPaper('A4');
        return $pdf->save('expedientes/'.$nameExpedient);
    }

    //* guardar consulta que viene del template dinamico
    public function store(Request $request, $id, $consultaId)
    {
        $configuration = FormularioConfiguration::with('fields')->findOrFail($id);

        // Crear una nueva entrada del formulario
        $entry = FormularioEntry::create([
            'consulta_id' => $consultaId,
            'formulario_configuration_id' => $id,
            'idusrregistra' => Auth::user()->id,
            'paciente_id' => $request->paciente_id,
            'user_cita_id' => $request->user_cita_id,
        ]);

        // Guardar los valores de cada campo del formulario
        foreach ($configuration->fields as $field) {
            FormularioEntryField::create([
                'formulario_entry_id' => $entry->id,
                'formulario_field_id' => $field->id,
                'value' => $request->input('field_' . $field->id)
            ]);
        }
        self::saveExpedient($entry->id);
        return redirect()->back()->with('success', 'Formulario guardado correctamente');
    }

    public function updateFormularioConsulta(Request $request, $entryId)
    {
        $entry = FormularioEntry::findOrFail($entryId);

        // Validar y actualizar los campos editables
        foreach ($request->input('fields', []) as $fieldId => $value) {
            $entryField = FormularioEntryField::where('formulario_entry_id', $entry->id)
                ->where('formulario_field_id', $fieldId)
                ->first();

            if ($entryField) {
                $entryField->value = $value;
                $entryField->save();
            }
        }
        self::saveExpedient($entryId);
        return redirect()->back()
        ->with('success', 'Los cambios han sido guardados correctamente.');
    
    }
}

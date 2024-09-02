<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormularioConfiguration;
use App\Models\FormularioEntry;
use App\Models\FormularioEntryField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormularioController extends Controller
{
    public function show($id, $consultaId)
    {
        $configuration = FormularioConfiguration::with('fields')->findOrFail($id);
        return view('formularios.show', compact('configuration', 'consultaId'));
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

        return redirect()->back()->with('success', 'Formulario guardado correctamente');
    }
}

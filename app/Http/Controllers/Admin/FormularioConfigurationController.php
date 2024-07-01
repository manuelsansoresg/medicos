<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormularioConfiguration;
use App\Models\FormularioEntry;
use App\Models\FormularioEntryField;
use App\Models\FormularioField;
use Illuminate\Http\Request;

class FormularioConfigurationController extends Controller
{

    public function showFormularioGuardado($entryId)
    {
        $entry = FormularioEntry::with('fields.field')->findOrFail($entryId);
        return view('formulario_configurations.show_saved', compact('entry'));
    }
    public function showFormulario($configurationId, $consultaId)
    {
        $configuration = FormularioConfiguration::with('fields')->findOrFail($configurationId);
        return view('formulario_configurations.show', compact('configuration', 'consultaId'));
    }

    public function storeFormulario(Request $request, $configurationId, $consultaId)
    {
        $configuration = FormularioConfiguration::with('fields')->findOrFail($configurationId);

        // Crear una nueva entrada de formulario
        $entry = FormularioEntry::create([
            'consulta_id' => $consultaId,
            'formulario_configuration_id' => $configurationId,
        ]);

        // Guardar los valores de los campos del formulario
        foreach ($configuration->fields as $field) {
            FormularioEntryField::create([
                'formulario_entry_id' => $entry->id,
                'formulario_field_id' => $field->id,
                'value' => $request->input($field->field_name),
            ]);
        }

        return redirect()->route('formulario_configurations.index');
    }
    
    public function index()
    {
        $configurations = FormularioConfiguration::with('fields')->get();
        return view('formulario_configurations.index', compact('configurations'));
    }

    public function create()
    {
        return view('formulario_configurations.create');
    }

    public function store(Request $request)
    {
        $configuration = FormularioConfiguration::create(['name' => $request->name]);
        
        foreach ($request->fields as $field) {
            FormularioField::create([
                'formulario_configuration_id' => $configuration->id,
                'field_name' => $field['name'],
                'field_type' => $field['type'],
                'is_required' => isset($field['is_required']) ? 1 : 0,
                'options' => $field['options'] ?? null,
            ]);
        }

        return redirect()->route('formulario_configurations.index');
    }

    public function edit($configurationId)
    {
        $configuration = FormularioConfiguration::find($configurationId);
        $configuration->load('fields');
        return view('formulario_configurations.edit', compact('configuration'));
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

        return redirect()->route('formulario_entries.show_saved', $entry->id)
            ->with('success', 'Los cambios han sido guardados correctamente.');
    }

    public function update(Request $request, $configurationId)
    {
        $configuration = FormularioConfiguration::find($configurationId);
        $configuration->update(['name' => $request->name]);
        $configuration->fields()->delete();
    
        foreach ($request->fields as $field) {
            FormularioField::create([
                'formulario_configuration_id' => $configuration->id,
                'field_name' => $field['name'],
                'field_type' => $field['type'],
                'is_required' => isset($field['is_required']) ? 1 : 0,
                'options' => $field['options'] ?? null,
            ]);
        }
    
        return redirect()->route('formulario_configurations.index');
    }

    public function destroy($configurationId)
    {
        $configuration = FormularioConfiguration::find($configurationId);
        $configuration->delete();
        return redirect()->route('formulario_configurations.index');
    }
}

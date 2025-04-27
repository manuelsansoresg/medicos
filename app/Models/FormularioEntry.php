<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioEntry extends Model
{
    use HasFactory;
    protected $fillable = ['consulta_id', 'formulario_configuration_id', 'paciente_id', 'user_cita_id', 'idusrregistra', 'archivo'];

    public static function getFieldByEntryId($entryId)
    {
        $getFields = FormularioEntryField::select('formulario_fields.field_name', 'formulario_fields.field_type', 'formulario_fields.options', 'formulario_entry_fields.value')
                    ->join('formulario_fields', 'formulario_fields.id', 'formulario_entry_fields.formulario_field_id')
                    ->join('formulario_entries', 'formulario_entries.id', 'formulario_entry_fields.formulario_entry_id')
                    ->where('formulario_entry_id', $entryId)
                    ->get();
        return $getFields;
    }

    public function configuration()
    {
        return $this->belongsTo(FormularioConfiguration::class);
    }

    public function fields()
    {
        return $this->hasMany(FormularioEntryField::class, 'formulario_entry_id', 'id');
    }
}

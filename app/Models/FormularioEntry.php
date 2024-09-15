<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioEntry extends Model
{
    use HasFactory;
    protected $fillable = ['consulta_id', 'formulario_configuration_id', 'paciente_id', 'user_cita_id', 'idusrregistra'];

    public static function getFieldByEntryId($entryId)
    {
        \DB::enableQueryLog(); // Enable query log
        //$getForm = FormularioEntry::find($entryId);
        $getFields = FormularioEntryField::select('formulario_fields.field_name', 'formulario_fields.field_type', 'formulario_entry_fields.value')
                    ->join('formulario_fields', 'formulario_fields.id', 'formulario_entry_fields.formulario_field_id')
                    ->join('formulario_entries', 'formulario_entries.id', 'formulario_entry_fields.formulario_entry_id')
                    ->join('field_config_downloads', 'field_config_downloads.formulario_field_id', 'formulario_entry_fields.formulario_field_id')
                    ->join('users', 'users.id', 'formulario_entries.paciente_id')
                    ->where([
                    'formulario_entry_id' => $entryId,
                    'field_config_downloads.is_download' => 1, 
                    ])
                    ->whereColumn('field_config_downloads.user_id', 'users.usuario_principal')
                    ->get();
        //dd(\DB::getQueryLog()); // Show results of log
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

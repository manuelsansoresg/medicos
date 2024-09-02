<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioEntryField extends Model
{
    use HasFactory;

    protected $fillable = ['formulario_entry_id', 'formulario_field_id', 'value'];

    public static function getFields($formulario_entry_id)
    {
        $fields = FormularioEntryField::select('formulario_fields.field_name', 'formulario_entry_fields.value')
                ->join('formulario_fields', 'formulario_fields.id', 'formulario_entry_fields.formulario_field_id')
                ->where('formulario_entry_id', $formulario_entry_id)
                ->get()
                ->groupBy('formulario_entry_id')
                ->map(function($items) {
                    $formulario = [];
                    foreach ($items as $item) {
                        $formulario[] = array('name' =>$item->field_name, 'value' => $item->value);
                    }
                    return $formulario;
                })
                ->first(); // Si solo necesitas una fila, puedes usar first().
        
        return $fields;

    }

    public function field()
    {
        return $this->belongsTo(FormularioField::class, 'formulario_field_id', 'id');
    }
}

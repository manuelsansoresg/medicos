<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioEntryField extends Model
{
    use HasFactory;

    protected $fillable = ['formulario_entry_id', 'formulario_field_id', 'value'];

    public function field()
    {
        return $this->belongsTo(FormularioField::class, 'formulario_field_id', 'id');
    }
}

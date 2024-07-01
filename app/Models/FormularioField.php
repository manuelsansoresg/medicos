<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioField extends Model
{
    use HasFactory;
    protected $fillable = ['formulario_configuration_id', 'field_name', 'field_type', 'is_required', 'options'];

    public function configuration()
    {
        return $this->belongsTo(FormularioConfiguration::class);
    }

    public function entries()
    {
        return $this->hasMany(FormularioEntryField::class, 'formulario_field_id', 'id');
    }
}

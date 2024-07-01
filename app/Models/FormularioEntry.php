<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioEntry extends Model
{
    use HasFactory;
    protected $fillable = ['consulta_id', 'formulario_configuration_id'];


    public function configuration()
    {
        return $this->belongsTo(FormularioConfiguration::class);
    }

    public function fields()
    {
        return $this->hasMany(FormularioEntryField::class, 'formulario_entry_id', 'id');
    }
}

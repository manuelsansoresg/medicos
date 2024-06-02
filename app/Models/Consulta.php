<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_cita_id',
        'paciente_id',
        'idusrregistra',
        'fecha',
        'motivo',
        'peso',
        'receta',
    ];
}

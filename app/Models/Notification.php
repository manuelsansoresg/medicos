<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'idusrregistra',
        'msg',
        'leido',
    ];

    static $titles = array(
        'solicitud' => 'Solicitud',
        'vinculacion' => 'Vinculación',
        'actividad' => 'Actividad',
        'cita' => 'Cita',
        'paciente' => 'Paciente',
    );
}

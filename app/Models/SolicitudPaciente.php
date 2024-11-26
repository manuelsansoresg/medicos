<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPaciente extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitud_id',
        'paciente_id'
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}

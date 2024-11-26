<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudConsultorio extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitud_id',
        'consultorio_id',
        'isRenovacion'
    ];
}

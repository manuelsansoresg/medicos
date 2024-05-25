<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class UserCita extends Model
{
    use HasFactory;
    protected $fillable = [
        'iddoctor',
        'paciente_id',
        'hora',
        'fecha',
        'motivo',
        'id_consultorio',
        'id_clinica',
        'status',
        'consulta_asignado_id',
    ];

    public static function saveEdit($request)
    {
        $data                   = $request->data;
        $clinica                = Session::get('clinica');
        $consultorio            = Session::get('consultorio');
        $data['id_consultorio'] = $consultorio;
        $data['id_clinica']     = $clinica;
        $data['status']         = 1;

        $exist = UserCita::where([
            'iddoctor'       => $data['iddoctor'],
            'fecha'          => $data['fecha'],
            'hora'           => $data['hora'],
            'id_consultorio' => $data['id_consultorio'],
            'id_clinica'     => $data['id_clinica'],
            'status'         => 1,
            ]);
        if ($exist->count() == 0) {
            $userCita = UserCita::create($data);
        } else {
            $userCita = $exist->update($data);
        }
        return $userCita;
    }
}

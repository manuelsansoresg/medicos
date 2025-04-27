<?php

namespace App\Models;

use App\Mail\NotificationEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
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
        'status', // 1: Confirmada, 2: En consulta, 3: Cancelada
        'consulta_asignado_id',
    ];

    public static function saveEdit($request)
    {
        $data                   = $request->data;
        $clinica                = $request->clinica;
        $consultorio            = $request->consultorio;
        $data['id_consultorio'] = $consultorio == 0 ?  $data['id_consultorio'] : $consultorio ;
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
        
        $paciente = User::find($data['paciente_id']);
        if ($paciente != null) {
            $nombre = $paciente->name.' '.$paciente->vapellido;
            $clinica = Clinica::find( $data['id_clinica']);
            $consultorio = Consultorio::find($data['id_consultorio']);
            $dataCita = array(
                'type' => 'cita',
                'nombre' => $nombre,
                'fecha' => $data['fecha'],
                'hora' => $data['hora'],
                'clinica' => $clinica->tnombre,
                'direccion' => $clinica->tdireccion,
                'telefono' => $clinica->ttelefono,
                'consultorio' => $consultorio->vnumconsultorio,
                'from' => $paciente->email,
            );
            try {
                Mail::to($paciente->email)->send(new NotificationEmail($dataCita));
            } catch (\Exception $th) {
                //throw $th;
            }
            
        }
        return $userCita;
    }

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}

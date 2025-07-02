<?php

namespace App\Models;

use App\Mail\NotificationEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'id_clinica');
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'id_consultorio');
    }

    public function consultas()
    {
        return $this->hasMany(FormularioEntry::class, 'user_cita_id');
    }

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'user_cita_id');
    }

    public static function getCitasWithDetails($filters = [])
    {
        $query = UserCita::with(['paciente', 'clinica', 'consultorio', 'consultas', 'estudios'])
            ->where('status', '!=', 3); // Excluir citas canceladas

        // Filtros
        if (!empty($filters['clinica'])) {
            $query->where('id_clinica', $filters['clinica']);
        }

        if (!empty($filters['consultorio'])) {
            $query->where('id_consultorio', $filters['consultorio']);
        }

        if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_final'])) {
            $query->whereBetween('fecha', [$filters['fecha_inicio'], $filters['fecha_final']]);
        } elseif (!empty($filters['fecha_inicio'])) {
            $query->where('fecha', '>=', $filters['fecha_inicio']);
        } elseif (!empty($filters['fecha_final'])) {
            $query->where('fecha', '<=', $filters['fecha_final']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas('paciente', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('vapellido', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('codigo_paciente', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filtrar por permisos de usuario - se manejará en el componente Livewire
        // Aquí solo aplicamos filtros básicos de clínica/consultorio si están en sesión
        $clinica = session('clinica');
        $consultorio = session('consultorio');
        
        if ($clinica) {
            $query->where('id_clinica', $clinica);
        }
        if ($consultorio) {
            $query->where('id_consultorio', $consultorio);
        }

        return $query->orderBy('fecha', 'desc')->orderBy('hora', 'desc');
    }

    public static function getPacientesWithCitas($filters = [])
    {
        $citas = self::getCitasWithDetails($filters)->get();
        
        // Agrupar por paciente
        $pacientes = $citas->groupBy('paciente_id')->map(function($citasPaciente, $pacienteId) {
            $paciente = $citasPaciente->first()->paciente;
            
            // Verificar si el paciente existe antes de asignar propiedades
            if ($paciente === null) {
                return null; // Omitir pacientes nulos
            }
            
            $paciente->citas = $citasPaciente;
            $paciente->total_consultas = $citasPaciente->sum(function($cita) {
                return $cita->consultas->count();
            });
            $paciente->total_estudios = $citasPaciente->sum(function($cita) {
                return $cita->estudios->count();
            });
            return $paciente;
        })->filter(function($paciente) {
            return $paciente !== null; // Filtrar pacientes nulos
        });

        return $pacientes;
    }
}

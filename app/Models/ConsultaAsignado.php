<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Http\Client\Events\ConnectionFailed;
use Illuminate\Support\Facades\Log;

class ConsultaAsignado extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'consultasignado';
    protected $primaryKey = 'idconsultasignado';
    protected $fillable = [
        'idconsultasignado', 'iddoctor', 'idclinica', 'ihorainicial', 'ihorafinal', 'idia', 'iturno', 'itiempo', 'dfechaalta', 'idalta', 'idconsultorio', 'itipousr'
    ];


    public static function getMyCon($userId)
    {
        \DB::enableQueryLog(); // Enable query log
        $user       = User::find(Auth::user()->id);
        /*
        $is_admin   = Auth::user()->hasRole(['medico', 'auxiliar', 'secretario']); */
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        $query = ConsultaAsignado::select('iddoctor', 'idconsultorio')
        ->where('idclinica', $clinica)
        ->where('iddoctor', $userId)
        ->groupBy('iddoctor', 'idconsultorio')
        ->get();
    
        //dd(\DB::getQueryLog()); // Show results of log
        return $query;
    }

    public static function getByDate($date, $doctor_id = null, $isGroup = true)
    {
        \DB::enableQueryLog(); // Enable query log

        $doctor_id     = $doctor_id == null ? Auth::user()->id : $doctor_id;
        $dateTime      = new DateTime($date);
        $dayOfWeek     = $dateTime->format('w');
        $dayOfWeek     = $dayOfWeek == 0 ? 7  : $dayOfWeek;
        $idclinica     = Session()->get('clinica');
        $idconsultorio = Session()->get('consultorio');
        $turnos = array(1=> 'mañana', 2=> 'tarde', 3 => 'noche');

        //dd($idclinica, $doctor_id, $dayOfWeek);

        $consulta = ConsultaAsignado::where([
            'consultasignado.idclinica' => $idclinica,
            'consultasignado.iddoctor' => $doctor_id,
            'consultasignado.idia' => $dayOfWeek
        ])
        ->where('ihorainicial', '>', '0')
            ->where(function ($query) {
                $query->whereIn('iturno', [1, 2, 3])
                    ->orWhereNull('iturno');
            })
            ->join('consultorios', 'consultorios.idconsultorios', '=', 'consultasignado.idconsultorio')
            ->orderBy('idconsultorio', 'ASC')
            ->orderBy('iturno', 'ASC')
            ->get();
        
        $resultados = [];
        //dd($consulta);
        foreach ($consulta as $asignado) {
            $ihorainicial   = $asignado->ihorainicial;
            $ihorafinal     = $asignado->ihorafinal;
            $itiempo        = $asignado->itiempo;
            $momento        = $asignado->iturno;

            $contorhoras = 0;
            $horanueva = $ihorainicial;
            $horarios = [];
           
            while ($horanueva < $ihorafinal) {
                $horaFormateada = sprintf("%02d:%02d", floor($horanueva), ($horanueva - floor($horanueva)) * 60);
                
                // Check if the provided date is equal to the current date
                $isCurrentDate = $dateTime->format('Y-m-d') == now()->format('Y-m-d');
                // verificar disponibilidad
                //*revisar si el $date es correcto si no poner el date de la fecha actual del servidor
                $horaampm =  date("g:i a", strtotime($horaFormateada));
                $isDisponible = UserCita::where([
                                'consulta_asignado_id' => $asignado->idconsultasignado,
                                'hora' => $horaampm,
                                'status' => 1,
                                ])
                                ->first();
                // Validar si la hora actual es menor a la hora que estamos procesando (only if the date is the current date)
                $horarios[] = [
                    'id' => $asignado->idconsultasignado,
                    'hora' => date("g:i a", strtotime($horaFormateada)),
                    'horaSinFormato' => $horaFormateada,
                    'statusconactivanop' => $isDisponible == null ? false : true,
                    'diasemana' => $dayOfWeek,
                    'momento' => $momento,
                    'asignado' => $asignado->idconsultasignado,
                    'user_cita_id' => $isDisponible == null ? null :  $isDisponible->id,
                ];

                /* if ($isCurrentDate && $horaFormateada > now()->format('H:i')) {
                    $horarios[] = [
                        'id' => $asignado->idconsultasignado,
                        'hora' => date("g:i a", strtotime($horaFormateada)),
                        'horaSinFormato' => $horaFormateada,
                        'statusconactivanop' => $isDisponible == null ? false : true,
                        'diasemana' => $dayOfWeek,
                        'momento' => $momento,
                        'asignado' => $asignado->idconsultasignado,
                        'isDisponible' => $isDisponible == null ? 'libre' : 'ocupado',
                    ];
                } elseif (!$isCurrentDate) {
                    // If the date is not the current date, add all time slots without considering the current time
                    $horarios[] = [
                        'id' => $asignado->idconsultasignado,
                        'hora' => date("g:i a", strtotime($horaFormateada)),
                        'horaSinFormato' => $horaFormateada,
                        'statusconactivanop' => $isDisponible == null ? false : true,
                        'diasemana' => $dayOfWeek,
                        'momento' => $momento,
                        'asignado' => $asignado->idconsultasignado,
                        'isDisponible' => $isDisponible == null ? 'libre' : 'ocupado',
                    ];
                } */

                $horanueva += $itiempo / 60; // Aumenta la hora actual en el intervalo de tiempo
            }

            if (!empty($horarios)) {
                $resultados[$asignado->vnumconsultorio.' '.$turnos[$momento]] = [
                    'consultorio' => $asignado->vnumconsultorio,
                    'horarios' => $horarios,
                    'turno' => $momento,
                    'id' => $asignado->idconsultasignado,
                ];
            }
        }

        if ($isGroup) {
            // ... (código existente)
            $consulta->groupBy('iturno');
        }

        
        
        //dd(\DB::getQueryLog()); // Show results of log
        return $resultados;
    }

    public static function getByDay()
    {
        /* ConsultaAsignado::where([
            'iddoctor' => Auth::user()->id,
        ]); */
        $today = date('Y-m-d');
        return ConsultaAsignado::
             join('consultorios', 'consultorios.idconsultorios', 'consultasignado.idconsultorio')
            ->join('user_citas', 'user_citas.consulta_asignado_id', 'consultasignado.idconsultasignado')
            ->where('ihorainicial', '>', 0) 
            ->where('user_citas.fecha', $today)
            ->get();
    }

    public static function saveEdit($request)
    {
        //$requestData    = $request->all();
        $dataRequest   = $request->data;
        $idclinica     = Session()->get('clinica');
        $idconsultorio = $dataRequest['idconsultorio'];
        $iddoctor      = $request->userId;

        $horarios = [
            'manana' => [
                'ini' => $request->manana_ini,
                'fin' => $request->manana_fin,
            ],
            'tarde' => [
                'ini' => $request->tarde_ini,
                'fin' => $request->tarde_fin,
            ],
            'noche' => [
                'ini' => $request->noche_ini,
                'fin' => $request->noche_fin,
            ],
        ];
        $dataArray = array();
        foreach ($horarios as $turno => $horario) {

            foreach ($horario['ini'] as $keyDiaIni => $horaIni) {
                $idia = $keyDiaIni + 1;
                $data = array(
                    'idia' => $idia,
                    'iturno' => $turno === 'manana' ? 1 : ($turno === 'tarde' ? 2 : 3),
                    'idconsultorio' => $idconsultorio,
                    'idclinica' => $idclinica,
                    'itiempo' => $request->duraconsulta,
                    'iddoctor' => $iddoctor,
                );

                $dataArray[] =  array(
                    'idia' => $idia,
                    'iturno' => $turno === 'manana' ? 1 : ($turno === 'tarde' ? 2 : 3),
                    'idconsultorio' => $idconsultorio,
                    'idclinica' => $idclinica,
                    'itiempo' => $request->duraconsulta,
                    'iddoctor' => $iddoctor,
                );
                
                $consulta = ConsultaAsignado::where($data)->count();

                if ($consulta == 0) {
                    $data['iddoctor'] = $iddoctor;
                    $data['ihorainicial'] = $horaIni;
                    $data['ihorafinal'] = $horario['fin'][$keyDiaIni];
                    $data['dfechaalta'] = date('Y-m-d');
                    $data['idalta'] = 1;
                    ConsultaAsignado::create($data);
                } else {
                    $consulta = ConsultaAsignado::where($data)->update([
                        'ihorainicial' => $horaIni,
                        'ihorafinal' => $horario['fin'][$keyDiaIni],
                    ]);
                }
               
            }
        }
        return $dataArray;
    }
}

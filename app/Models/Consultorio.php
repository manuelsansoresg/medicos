<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Consultorio extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey   = 'idconsultorios';
    protected $table        = 'consultorios';
    protected $fillable = [
        'idconsultorios','vnumconsultorio','thubicacion','ttelefono','idclinica', 'idusrregistra', 'status'
    ];

    public static function getAll($paginate = null, $status = null, $setUserId = null)
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        $isAdmin     = Auth::user()->hasRole('administrador');
        $userId = User::getMyUserPrincipal();

        if ($isAdmin) {
            if ($setUserId == null) {
                $consultorio =  Consultorio::all();
            } else {
                $consultorio =  Consultorio::where([
                    'idusrregistra' => $setUserId,
                ])->get();; 
            }
        } else {
            $consultorio =  Consultorio::where([
                'idusrregistra' => $userId,
            ]); 
            if ($status != 0) {
                $consultorio->where('status', $status);
            }
            $consultorio = $consultorio->get();
        }

        return $consultorio;

       /*  if ($consultorio == 0 && $consultorio != null) {
            return Consultorio::getAsignedConsultories($clinica);
        }
        return Consultorio::where([
            'idconsultorios' => $consultorio,
            'idclinica' => $clinica,
        ])->get(); */
    }

    public static function getAsignedConsultories($clinicaId, $isArray = false)
    {
        $isAdmin           = Auth::user()->hasRole('administrador');
        $isMedico          = Auth::user()->hasRole('medico');
        $isAuxiliar        = Auth::user()->hasRole('auxiliar');
        $usuario_principal = User::getMyUserPrincipal();
        $idConsultorios    = array();
        $consultorios      = null;

        if ($isAdmin) {
            $consultorios =  Consultorio::all();
        } elseif ($isMedico) {
            $getConsultaAsignado = ConsultaAsignado::select('idconsultorio')
            ->where([
                'idclinica' => $clinicaId
            ])
            ->where(function($query) use ($usuario_principal) {
                $query->where('iddoctor', Auth::user()->id)
                      ->orWhere('iddoctor', $usuario_principal);
            })->groupBy('idconsultorio');
            if ($getConsultaAsignado->count() > 0) {
                $getConsultaAsignado = $getConsultaAsignado->get()->toArray();
                $consultorios =  Consultorio::whereIn('idconsultorios', $getConsultaAsignado)->get();
            }
        } else {
            $getConsultaAsignado = ConsultaAsignado::select('idconsultorio')
            ->where([
                'idclinica' => $clinicaId,
                'iddoctor' => Auth::user()->id
            ])->groupBy('idconsultorio');
            if ($getConsultaAsignado->count() > 0) {
                $getConsultaAsignado = $getConsultaAsignado->get()->toArray();
                $consultorios =  Consultorio::whereIn('idconsultorios', $getConsultaAsignado)->get();
            }
        }

        if ($isArray === true) {
            if ($consultorios != null) {
                foreach ($consultorios as $getConsultorio) {
                    $idConsultorios[] = $getConsultorio->idconsultorios;
                }
            }
            return $idConsultorios;
        }
        return $consultorios; 
    }

    public static function getMyCon()
    {
        /* $user       = User::find(Auth::user()->id);
        $is_admin   = Auth::user()->hasRole(['medico', 'auxiliar', 'secretario']); */
        $clinica                  = Session::get('clinica');
        
        return Consultorio::where(['idclinica'=> $clinica])->get();
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $consultorio_id = $request->consultorio_id;
        $userId = User::getMyUserPrincipal();
        if ($consultorio_id == null) {
            $data['idusrregistra'] = $userId;
            $consultorio = Consultorio::create($data);
            VinculacionSolicitud::saveVinculacion($consultorio->idconsultorios, 'totalConsultorioExtra'); 
        } else {
            $consultorio = Consultorio::find($consultorio_id);
            $consultorio->update($data);
        }
        return $consultorio;
    }

    public static function updateConFinishDate()
    {
        // Obtener el usuario principal
        $userId = User::getMyUserPrincipal();

        // Consultar solicitudes del usuario principal
        $solicitudes = Solicitud::where('user_id', $userId)
            ->where('estatus', 1) // Solo solicitudes activas
            ->whereIn('catalog_prices_id', [1,3])
            ->get();

        // Determinar usuarios permitidos con base en solicitudes válidas
        $consultoriosPermitidos = 0;
        foreach ($solicitudes as $solicitud) {
            if (Carbon::parse($solicitud->fecha_vencimiento)->isFuture()) {
                if ($solicitud->catalog_prices_id == 1) {
                    $consultoriosPermitidos += 2; // 2 usuarios para catalog_prices_id = 1
                } elseif ($solicitud->catalog_prices_id == 3) {
                    $consultoriosPermitidos += $solicitud->cantidad; // Cantidad definida
                }
            }
        }

        // Obtener la lista de usuarios ascendentes
        $consultorios = Consultorio::getAll();

        // Actualizar el estado de los usuarios
        $contador = 0;
        foreach ($consultorios as $consultorio) {
            if ($contador < $consultoriosPermitidos) {
                $consultorio->status = 1; // Mantener activo
                //echo 'activo'. $consultorio->name.'<br>';
            } else {
                $consultorio->status = 0; // Desactivar el resto
                //echo 'inactivo'. $consultorio->name.'<br>';
            }
            $consultorio->update();
            $contador++;
        }
    }

    public function consultorioClinica()
    {
        return $this->belongsTo(Clinica::class, 'idclinica');
    }
}

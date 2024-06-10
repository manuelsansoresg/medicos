<?php

namespace App\Models;

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
        'idconsultorios','vnumconsultorio','thubicacion','ttelefono','idclinica'
    ];

    public static function getAll()
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        $isAdmin     = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            return Consultorio::all();
        }

        if ($consultorio == 0 && $consultorio != null) {
            return Consultorio::getAsignedConsultories($clinica);
        }
        return Consultorio::where([
            'idconsultorios' => $consultorio,
            'idclinica' => $clinica,
        ])->get();
    }

    public static function getAsignedConsultories($clinicaId, $isArray = false)
    {
        $isAdmin           = Auth::user()->hasRole('administrador');
        $isMedico          = Auth::user()->hasRole('medico');
        $isAuxiliar        = Auth::user()->hasRole('auxiliar');
        $usuario_principal = User::getMyUserPrincipal();
        $idConsultorios    = array();

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
            foreach ($consultorios as $getConsultorio) {
                $idConsultorios[] = $getConsultorio->idconsultorios;
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
        if ($consultorio_id == null) {
            $consultorio = Consultorio::create($data);
        } else {
            $consultorio = Consultorio::find($consultorio_id);
            $consultorio->update($data);
        }
        return $consultorio;
    }

    public function consultorioClinica()
    {
        return $this->belongsTo(Clinica::class, 'idclinica');
    }
}

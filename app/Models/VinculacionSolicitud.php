<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VinculacionSolicitud extends Model
{
    use HasFactory;
    protected $table = 'vinculacion_solicitud';
    protected $fillable = [
        'solicitudId',
        'user_id',
        'idusrregistra',
        'idRel',
    ];

    public static function vincularPaquete($solicitudId)
    {
        $solicitud = Solicitud::find($solicitudId);
        $getVinculacion = VinculacionSolicitud::where(['solicitudId'  => $solicitud->id, 'idRel' => $solicitud->user_id ])->count();
        if ($getVinculacion == 0 ) {
            VinculacionSolicitud::create(array(
                'user_id' => $solicitud->user_id,
                'idusrregistra' => Auth::user()->id,
                'solicitudId' => $solicitud->id,
                'idRel' => $solicitud->user_id,
            ));
        }

    }

    public static function addVinculacion($solicitudId, $idRel)
    {
        $solicitud = Solicitud::find($solicitudId);
        $getVinculacion = VinculacionSolicitud::where(['solicitudId'  => $solicitudId, 'idRel' => $idRel ])->count();
        if ($getVinculacion == 0 ) {
            VinculacionSolicitud::create(array(
                'user_id' => $solicitud->user_id,
                'idusrregistra' => Auth::user()->id,
                'solicitudId' => $solicitud->id,
                'idRel' => $idRel,
            ));
        }
        return $getVinculacion;
    }

    public static function saveVinculacion($idRel, $type)
    {
        $isMedico = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $data = array();
        if ($isMedico == true || $isAuxiliar == true) {
            $userPrincipal = User::getMyUserPrincipal();
            $data['user_id'] = Auth::user()->id;
            $data['idusrregistra'] = $userPrincipal;
            
    
            $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
            $solicitudId = $getUsedStatusPackages[$type]['solicitudId'];
            $data['solicitudId'] = $solicitudId;
            $data['idRel'] = $idRel;
            VinculacionSolicitud::create($data);


        }
    }

    public static function deleteVinculacion($idRel)
    {
        $isMedico = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        if ($isMedico == true || $isAuxiliar == true) {
            $userPrincipal = User::getMyUserPrincipal();
            $data['user_id'] = Auth::user()->id;
            $data['idusrregistra'] = $userPrincipal;
            $getUsedStatusPackages = Solicitud::getUsedStatusPackages();
            $solicitudId = $getUsedStatusPackages['totalClinica']['solicitudId'];
            $data['solicitudId'] = $solicitudId;
            $data['idRel'] = $idRel;
            VinculacionSolicitud::where($data)->delete();
        }
    }
}

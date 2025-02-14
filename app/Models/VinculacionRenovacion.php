<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VinculacionRenovacion extends Model
{
    use HasFactory;
    protected $table = 'vinculacion_renovacion';
    protected $fillable = [
        'solicitudId',
        'user_id',
        'idusrregistra',
        'idRel',
    ];

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
            VinculacionRenovacion::create($data);


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
            VinculacionRenovacion::where($data)->delete();
        }
    }
    
}

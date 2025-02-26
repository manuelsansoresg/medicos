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
        'catalog_prices_id',
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

    public static function addVinculacion($solicitudId, $idRel, $catalog_prices_id)
    {
        $solicitud = Solicitud::find($solicitudId);
        $getVinculacion = VinculacionSolicitud::where(['solicitudId'  => $solicitudId, 'idRel' => $idRel, 'catalog_prices_id' => $catalog_prices_id ])->count();
        if ($getVinculacion == 0 ) {
            VinculacionSolicitud::create(array(
                'user_id' => $solicitud->user_id,
                'idusrregistra' => Auth::user()->id,
                'solicitudId' => $solicitud->id,
                'idRel' => $idRel,
                'catalog_prices_id' => $catalog_prices_id,
            ));
        }
        return $getVinculacion;
    }

    public static function saveVinculacion($idRel, $type, $catalog_prices_id)
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
            $data['catalog_prices_id'] = $catalog_prices_id;
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

    public static function getMyVinculacion($solicitudId)
    {
        $getVinvulaciones = VinculacionSolicitud::select('catalog_prices.nombre', 'vinculacion_solicitud.idRel', 'vinculacion_solicitud.catalog_prices_id')
        ->join('solicitudes', 'solicitudes.id', 'vinculacion_solicitud.solicitudId')
        ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
        ->where('solicitudId', $solicitudId)->get();

        $clinica = array();
        $consultorio = array();
        $users = array();
        
        foreach ($getVinvulaciones as $getVinvulacion) {
            
            if ($getVinvulacion->nombre == 'Paquete básico') {
                if ($getVinvulacion->catalog_prices_id == 1) {
                    $getClinica = Clinica::find($getVinvulacion->idRel);
                    $clinica[] =  $getClinica->tnombre;
                }
                if ($getVinvulacion->catalog_prices_id == 2) {
                    $getCon = Consultorio::find($getVinvulacion->idRel);
                    $consultorio[] =  $getCon->vnumconsultorio;
                }
                
                if ($getVinvulacion->catalog_prices_id == 3) {
                    $getUser = User::find($getVinvulacion->idRel);
                    $users[] =  "{$getUser->name} {$getUser->vapellido} {$getUser->segundo_apellido}";
                }
                
                
            }
            
            if ($getVinvulacion->nombre == 'clinica extra') {
                $getClinica = Clinica::find($getVinvulacion->idRel);
                $clinica[] =  $getClinica->tnombre;
            }
            
            if ($getVinvulacion->nombre == 'consultorio extra') {
                $getCon = Consultorio::find($getVinvulacion->idRel);
                $consultorio[] =  $getCon->vnumconsultorio;
            }
            
            if ($getVinvulacion->nombre == 'Usuario extra') {
                $getUser = User::find($getVinvulacion->idRel);
                $users[] =  "{$getUser->name} {$getUser->vapellido} {$getUser->segundo_apellido}";
            }
        }
        return array('clinica' => $clinica, 'consultorio' => $consultorio, 'usuarios' => $users);
    }
}

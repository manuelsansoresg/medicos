<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudUsuario extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitud_id',
        'user_id',
        'isRenovacion',
    ];

    public static function saveRenew($request)
    {
        $users = isset($request->users)? $request->users : null;
        $cons = isset($request->cons)? $request->cons : null;
        $solicitudId = $request->solicitudId;
        $solicitud = Solicitud::find($solicitudId);
        
        if ($solicitud->catalog_prices_id == 2) {
            
            if ($users != null) { //viene con un valor asi que preparar la tabla para crear una renovación
                foreach ($users as $user) {
                    $dataUser = array(
                        'solicitud_id' => $solicitudId,
                        'user_id' => $user,
                        'isRenovacion' => 0,
                    );
                    SolicitudUsuario::create($dataUser);
                }
                Solicitud::reset($solicitudId);
            }
        }
        
        if ($solicitud->catalog_prices_id == 3) { //consultorios
            
            if ($cons != null) { //viene con un valor asi que preparar la tabla para crear una renovación
                foreach ($cons as $con) {
                    $dataCon = array(
                        'solicitud_id' => $solicitudId,
                        'consultorio_id' => $con,
                        'isRenovacion' => 0,
                    );
                    SolicitudConsultorio::create($dataCon);
                }
                Solicitud::reset($solicitudId);
            }
        }

    }

    public static function activateRenew($solicitudId)
    {
        $solicitud = Solicitud::find($solicitudId);

        if ($solicitud->catalog_prices_id == 2) {
            $getUsers = SolicitudUsuario::where([
                'solicitud_id' => $solicitud->solicitud_origin_id,
                'isRenovacion' => 0,
            ])->get();
    
            foreach ($getUsers as $getUser) {
                User::where('id', $getUser->user_id)->update([
                    'status' => 1
                ]);
            }
    
            $getUsers = SolicitudUsuario::where([
                'solicitud_id' => $solicitud->solicitud_origin_id,
                'isRenovacion' => 0,
            ])->update([
                'isRenovacion' => 1
            ]);
        }

        if ($solicitud->catalog_prices_id == 3) { //consultorios
            $getCons = SolicitudConsultorio::where([
                'solicitud_id' => $solicitud->solicitud_origin_id,
                'isRenovacion' => 0,
            ])->get();
    
            foreach ($getCons as $getCon) {
                Consultorio::where('idconsultorios', $getCon->consultorio_id)->update([
                    'status' => 1
                ]);
            }
    
            $getCons = SolicitudConsultorio::where([
                'solicitud_id' => $solicitud->solicitud_origin_id,
                'isRenovacion' => 0,
            ])->update([
                'isRenovacion' => 1
            ]);
        }

    }

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'idusrregistra',
        'title',
        'msg',
        'leido',
    ];

    static $titles = array(
        'solicitud' => 'Solicitud',
        'vinculacion' => 'Vinculación',
        'actividad' => 'Actividad',
        'cita' => 'Cita',
        'paciente' => 'Paciente',
    );

    public static function getAllNotifications($limit = 5)
    {
        $isAdmin = Auth::user()->hasRole('administrador');
        
       
        if($isAdmin){
            return Notification::orderBy('created_at', 'desc')
            ->paginate($limit);
        }else{
            return Notification::where('user_id', User::getMyUserPrincipal())  ->orderBy('created_at', 'desc')
            ->paginate($limit);
        }
    }

    public static function SolicitudPaquete($packageId, $userId)
    {
        $package = Package::find($packageId);
        $user = User::find($userId);
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Solicitud alta de paquete',
            'msg' => 'Solicitud para alta del paquete '.$package->nombre.' por transferencia de '.$user->name.' '.$user->vapellido.' '.$user->segundo_apellido,
            'leido' => 0,
        ]);
    }

    public static function vinculacionPaciente($pacienteId)
    {
        $paciente = User::find($pacienteId);
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Vinculación de paciente',
            'msg' => 'Vinculación de paciente '.$paciente->name.' '.$paciente->vapellido.' '.$paciente->segundo_apellido,
            'leido' => 0,
        ]);
    }

    public static function vinculacionClinica($clinicaId)
    {
        $clinica = Clinica::find($clinicaId);
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Vinculación de clinica',
            'msg' => 'Vinculación de clinica '.$clinica->tnombre,
            'leido' => 0,
        ]);
    }

    public static function vinculacionConsultorio($consultorioId)
    {
        $consultorio = Consultorio::find($consultorioId);
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Vinculación de consultorio',
            'msg' => 'Vinculación de consultorio '.$consultorio->vnumconsultorio,
            'leido' => 0,
        ]);
    }

    public static function vinculacionUsuario($usuarioId)
    {
        $usuario = User::find($usuarioId);
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Vinculación de usuario',
            'msg' => 'Vinculación de usuario '.$usuario->name.' '.$usuario->vapellido.' '.$usuario->segundo_apellido,
            'leido' => 0,
        ]);
    }

    public static function porVencer($solicitudId)
    {
        $solicitud = Solicitud::select(
            'solicitudes.id',
            DB::raw('CASE 
                WHEN solicitudes.source_id = 0 THEN packages.nombre 
                ELSE catalog_prices.nombre 
            END as nombre_solicitud'),
            DB::raw('CASE 
                WHEN solicitudes.source_id = 0 THEN packages.precio 
                ELSE catalog_prices.precio 
            END as precio'),
          
            'users.name',
            'users.vapellido',
            'solicitudes.fecha_vencimiento',
           
        )
        ->leftJoin('catalog_prices', function($join) {
            $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                ->where('solicitudes.source_id', '!=', 0);
        })
        ->leftJoin('packages', function($join) {
            $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                ->where('solicitudes.source_id', '=', 0);
        })
        ->join('users', 'users.id', '=', 'solicitudes.user_id')
        ->where('solicitudes.id', $solicitudId)
        ->first();
       
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Solicitud por vencer',
            'msg' => 'Solicitud por vencer '.$solicitud->nombre,
            'leido' => 0,
        ]);
    }

    public static function solicitudVencida($solicitudId)
    {
        $solicitud = Solicitud::select(
            'solicitudes.id',
            DB::raw('CASE 
                WHEN solicitudes.source_id = 0 THEN packages.nombre 
                ELSE catalog_prices.nombre 
            END as nombre_solicitud'),
            DB::raw('CASE 
                WHEN solicitudes.source_id = 0 THEN packages.precio 
                ELSE catalog_prices.precio 
            END as precio'),
          
            'users.name',
            'users.vapellido',
            'solicitudes.fecha_vencimiento',
           
        )
        ->leftJoin('catalog_prices', function($join) {
            $join->on('catalog_prices.id', '=', 'solicitudes.solicitud_origin_id')
                ->where('solicitudes.source_id', '!=', 0);
        })
        ->leftJoin('packages', function($join) {
            $join->on('packages.id', '=', 'solicitudes.solicitud_origin_id')
                ->where('solicitudes.source_id', '=', 0);
        })
        ->join('users', 'users.id', '=', 'solicitudes.user_id')
        ->where('solicitudes.id', $solicitudId)
        ->first();
       
        Notification::create([
            'user_id' => User::getMyUserPrincipal(),
            'idusrregistra' => Auth::user()->id,
            'title' => 'Solicitud vencida',
            'msg' => 'Solicitud vencida '.$solicitud->nombre,
            'leido' => 0,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PendienteUsr extends Model
{
    use HasFactory;
    protected $table = 'pendientesusr';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'pendiente',
        'status',
        'fecha',
        'hora',
        'idusrregistra',
    ];

    public static function getAll()
    {
        $isAdmin    = Auth::user()->hasRole('administrador');
        $usuario_principal = User::getMyUserPrincipal();
        if ($isAdmin) {
            $query = PendienteUsr::where('status', 1)->get();
        }  else {
            $query = PendienteUsr::
            where('idusrregistra', $usuario_principal)
            ->where('status', 1)
            ->get();
        }
       
        return $query;
    }

    public static function saveEdit($request)
    {
        $pendienteId           = $request->pendiente_id;
        $data                  = $request->data;
        

        if ($pendienteId == null ) {
            $data['idusrregistra'] = Auth::user()->id;
            $pendiente = PendienteUsr::create($data);
        } else {
            $pendiente = PendienteUsr::find($pendienteId);
            $pendiente->update($data);
        }
    }

    public static function getByDay()
    {
        $diadhoy           = date("Y-m-d");
        $isAdmin           = Auth::user()->hasRole('administrador');
        $usuario_principal = User::getMyUserPrincipal();

        if ($isAdmin) {
            $query = PendienteUsr::where('fecha', $diadhoy)->get();
        } else {
            $query = PendienteUsr::
            where('idusrregistra', $usuario_principal)
            ->where('fecha', $diadhoy)
            ->get();
        }
        return $query;
    }
}

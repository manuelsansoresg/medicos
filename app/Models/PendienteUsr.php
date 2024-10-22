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

    public static function getAll($paginate = null)
    {
        $isAdmin    = Auth::user()->hasRole('administrador');
        $usuario_principal = User::getMyUserPrincipal();
        if ($isAdmin) {
            $query = PendienteUsr::where('status', 1);
        }  else {
            $query = PendienteUsr::
            where('idusrregistra', $usuario_principal)
            ->where('status', 1)
            ;
        }
        if ($paginate != null) {
            $query->paginate($paginate);
        }
        return $query->get();
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

    public static function getByDay($paginate = null)
    {
        $diadhoy           = date("Y-m-d");
        $isAdmin           = Auth::user()->hasRole('administrador');
        $usuario_principal = User::getMyUserPrincipal();

        if ($isAdmin) {
            $query = PendienteUsr::where('fecha', $diadhoy);
        } else {
            $query = PendienteUsr::
            where('idusrregistra', $usuario_principal)
            ->where('fecha', $diadhoy)
            ;
        }
        if ($paginate != null) {
            return $query->paginate($paginate);
        }
        return $query->get();
    }
}

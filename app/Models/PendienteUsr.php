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
        $isDoctor   = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $myUser = User::find(Auth::user()->id);
        if ($isAdmin) {
            $query = PendienteUsr::all();
        } 
        if ($isDoctor === true) {
            $query = PendienteUsr::
                            where('idusrregistra', $myUser->usuario_principal)
                            ->get();
        }

        if ($isAuxiliar === true) {
            $query =  PendienteUsr::
                        where('idusrregistra', Auth::user()->id)
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
        $diadhoy  = date("Y-m-d"); 
        $isAdmin    = Auth::user()->hasRole('administrador');
        $isDoctor   = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $myUser = User::find(Auth::user()->id);

        if ($isAdmin) {
            $query = PendienteUsr::where('fecha', $diadhoy)->get();
        } 
        if ($isDoctor === true) {
            $query = PendienteUsr::
                            where('idusrregistra', $myUser->usuario_principal)
                            ->where('fecha', $diadhoy)
                            ->get();
        }

        if ($isAuxiliar === true) {
            $query =  PendienteUsr::
                        where('idusrregistra', Auth::user()->id)
                        ->where('fecha', $diadhoy)
                        ->get();
        }

        return $query;
    }
}

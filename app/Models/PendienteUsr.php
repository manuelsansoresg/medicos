<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PendienteUsr extends Model
{
    use HasFactory;
    protected $table = 'pendientesusr';
    protected $fillable = [
        'idpendientesusr',
        'tpendiente',
        'istatus',
        'dfecha',
        'vhora',
        'idusrregistra',
    ];

    public static function getByDay()
    {
        $diadhoy  = date("Y-m-d"); 
        /* return PendienteUsr::where('idusrregistra', Auth::user()->id)
                        ->where('dfecha', $diadhoy)->get(); */
        return PendienteUsr::all();
    }
}

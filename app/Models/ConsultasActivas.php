<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultasActivas extends Model
{
    use HasFactory;
    protected $table = 'consultasactivas';
    protected $fillable = [
        'idconsultasactivas','idconsultorio','idmedico','vhorainicia','dfecha','iddiacon','idturno','iestatus','idclinica','vhoracierra'
    ];

    public static function disponible($idconsultorio, $idldoctores, $fecha, $iddiacon, $idturno, $idclinica)
    {
        $activas = ConsultasActivas::select('iestatus')
                    ->where([
                        'idconsultorio' => $idconsultorio,
                        'idmedico' => $idldoctores,
                        'dfecha' => $fecha,
                        'iddiacon' => $iddiacon,
                        'idturno' => $idturno,
                        'idclinica' => $idclinica,
                    ])->first();
        return $activas;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConsultaAsignado extends Model
{
    use HasFactory;

    protected $table = 'consultasignado';
    protected $fillable = [
        'idconsultasignado','iddoctor','idclinica','ihorainicial','ihorafinal','idia','iturno','itiempo','dfechaalta','idalta','idconsultorio','itipousr'
    ];

    public static function getByDay()
    {
        /* ConsultaAsignado::where([
            'iddoctor' => Auth::user()->id,
        ]); */
        return ConsultaAsignado::
                join('consultorios', 'consultorios.idconsultorios', 'consultasignado.idconsultorio')
                ->get();
    }
}

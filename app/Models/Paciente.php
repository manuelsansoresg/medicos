<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Paciente extends Model
{
    use HasFactory;
    protected $table = 'lpaciente';
    protected $primaryKey = 'idlpaciente';
    protected $fillable = [
        'idlpaciente','vnombre','vapellido','ttelefono','vfechan','vfecham','vcodigopasiente','vpass','istatus','vpeso','tdireccion','vnumeroseguro','tobservaciones','vfoto','vruta','mail','idclinica','isexo','idreveusr'
    ];

    public static function getAll()
    {
        $clinica                  = Session::get('clinica');
        $consultorio              = Session::get('consultorio');
      
        return Paciente::where([
            'idclinica' => $clinica,
            'istatus' => 1,
        ])->get();
    }
    
    public static function search($query)
    {
        $clinica                  = Session::get('clinica');
        $consultorio              = Session::get('consultorio');
        //$pacientes = Paciente::where('vapellido', 'LIKE', '%' . $query . '%')
        $pacientes = Paciente::
            where([
            'idclinica' => $clinica,
            'istatus' => 1,
        ])
        ->limit(40)
        ->get();
        $newPacientes = array();
        foreach ($pacientes as $paciente) 
        {
            $newPacientes[] = array(
                'id' => $paciente->idlpaciente,
                'text' => $paciente->vnombre.' '.$paciente->vapellido,
            );    
        }
        return $newPacientes;
    }
}

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

    

    public static function search($request)
    {
        $clinica     = Session::get('clinica');
        $consultorio = Session::get('consultorio');
        $search      = $request->search;
        //$pacientes = Paciente::where('vapellido', 'LIKE', '%' . $query . '%')
        $pacientes = Paciente::
            where([
            'idclinica' => $clinica,
            'istatus' => 1,
        ])
        ->where(function ($query) use ($search) {
            $query->orWhere('vnombre', 'LIKE', "%$search%");
        })
        ->get();
        return $pacientes;
       /*  $newPacientes = array();
        foreach ($pacientes as $paciente) 
        {
            $newPacientes[] = array(
                'id' => $paciente->idlpaciente,
                'text' => $paciente->vnombre.' '.$paciente->vapellido,
            );    
        }
        return $newPacientes; */
    }
}

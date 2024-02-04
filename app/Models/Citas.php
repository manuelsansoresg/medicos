<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Citas extends Model
{
    use HasFactory;
    protected $table = 'citas';
    protected $fillable = [
        'idcitas','vfoliopaciente','tmotivoconsulta','dfecha','vhora','idregistrado','iddoctor','idclinica','idconsultorio','idcliente','istatuscita','idiaconsulta','idiasemana','iformacita'
    ];
    // Desactivar las marcas de tiempo
    public $timestamps = false;

    public static  function faltandias($fecha) {
        
        $fechaActual = new DateTime();
        $fechaLimite = new DateTime($fecha);
    
        // Establecer la fecha límite al final del mismo año en que se proporciona la fecha
        $fechaLimite->setDate($fechaLimite->format('Y'), 12, 31)->setTime(23, 59, 59);
    
        // Calcular la diferencia
        $diferencia = $fechaActual->diff($fechaLimite);
    
        // Obtener la cantidad de días restantes
        $dias = $diferencia->days;
    
        return $dias -1;
    }

    public static function saveEdit($request)
    {
        
        
        $data                     = $request->data;
        
        $diasm = $diasm = Citas::faltandias($data['dfecha']);
        $diastrans = 365 - $diasm;
        $userId                   = Auth::user()->id;
        $user                     = User::find($userId);
        $isNotAdminOrDoctor       = $user->hasRole(['auxiliar', 'secretario']);
        $paciente                 = Paciente::find($data['idcliente']);
        $lidldoctores             = $request->lidldoctores;
        $idldoctores              = $userId;
        $data['vfoliopaciente']   = $paciente->vcodigopasiente;
        $data['idclinica']        = Session::get('clinica');
        $data['idconsultorio']    = $request->idconsultorio;
        //$data['idcliente']        = $request->idcliente;
        $data['idiaconsulta']     = $diastrans;
        
        
        

        if ($isNotAdminOrDoctor == false) {
            $altacita = $idldoctores;
            $iddoctor = $idldoctores;
        } else {
            $altacita = $idldoctores;
            $iddoctor  = $lidldoctores;
        }
        $data['idregistrado']   = $altacita;
        $data['iddoctor']   = $iddoctor;
        if ($request->id_cita == null) {
            return Citas::create($data);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FechaEspeciales extends Model
{
    use HasFactory;
    protected $primaryKey   = 'idfechaespeciales';
    protected $table = 'fechaespeciales';
    public $timestamps = false;
    protected $fillable = [
        'idfechaespeciales','idconsultorio','iddoctor','dfecha','tmotivo','idclinica','dfechafin'
    ];

    public static function getAll()
    {
        $consultorio = Session()->get('consultorio');
        $clinica     = Session()->get('clinica');
        $isAdmin     = Auth::user()->hasRole('administrador');
        if ($isAdmin) {
            return FechaEspeciales::all();
        }
        if ($consultorio == 0) {
            $getAsignedConsultories = Consultorio::getAsignedConsultories($clinica, true);
            return FechaEspeciales::where([
                'idclinica' => $clinica,
            ])
            ->whereIn('idconsultorio', $getAsignedConsultories)    
            ->get();
        }
        return FechaEspeciales::where([
            'idclinica' => $clinica,
            'idconsultorio' => $consultorio,
        ])->get();
    }

    public static function getByDate($date)
    {
        $doctor_id   = Auth::user()->id;
        $idclinica   = session()->get('clinica');
        $consultorio = Session()->get('consultorio');
        
        $fechas = FechaEspeciales::where(['iddoctor'=> $doctor_id, 'idclinica' => $idclinica])
                                ->where('dfecha', '<=', $date)
                                ->where('dfechafin', '>=', $date);
        if ($consultorio != 0) 
        {
            $fechas->where('iddoctor', $consultorio);
        }
        return $fechas = $fechas->get();
    }

    public static function saveEdit($request)
    {
        $data                     = $request->data;
        $idfechaespeciales        = $request->id;
        $clinica                  = Session::get('clinica');
        $consultorio              = Session::get('consultorio');
        if ($consultorio != 0) {
            $data['idconsultorio']    = $consultorio;
        }

        if ($idfechaespeciales == null) {
            
            $data['idclinica']        = $clinica;
            $data['iddoctor']         = Auth::user()->id;
            $fechas_especiales = FechaEspeciales::create($data);
        } else {
            $fechas_especiales = FechaEspeciales::find($idfechaespeciales);
            $fechas_especiales->update($data);
        }
        return $fechas_especiales;
    }

}

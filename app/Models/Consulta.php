<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Consulta extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_cita_id',
        'paciente_id',
        'idusrregistra',
        'fecha',
        'motivo',
        'exploracion',
        'peso',
        'receta',
    ];

    public static function getByPaciente($pacienteId,  $search = null, $limit = null, $isPaginate = false)
    {
        $query =  Consulta::
                        where('paciente_id', $pacienteId);
        if ($search != '') {
            $query->where('motivo', 'like', '%' . $search . '%');
            $query->orWhere('fecha', 'like', '%' . $search . '%');
        }

        $limit = $limit === null ? 50 : $limit;
        if ($isPaginate === true) {
            $query = $query->paginate($limit);
        } else {
            $query = $query->get();
        }
        return $query;
    }

    public static function saveEdit ($request)
    {
        $data = $request->data;
        $consulta_id = $request->consulta_id;
        if ($consulta_id == null) {
            $data['fecha'] = date('Y-m-d');
            $data['idusrregistra'] = Auth::user()->id;
            Consulta::create($data);
        } else {
            Consulta::where('id', $consulta_id)->update($data);
        }
       
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Estudio extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_cita_id',
        'paciente_id',
        'idusrregistra',
        'estudios',
        'diagnosticos',
    ];

    public static function saveEdit ($request)
    {
        $data = $request->data;
        $estudio_id = $request->estudio_id;
        if ($estudio_id == null) {
            $data['idusrregistra'] = Auth::user()->id;
            Estudio::create($data);
        } else {
            Estudio::where('id', $estudio_id)->update($data);
        }
    }

    public static function getByPaciente($pacienteId,  $search = null, $limit = null, $isPaginate = false)
    {
        $query =  Estudio::
                        where('paciente_id', $pacienteId);
        if ($search != '') {
            $query->where('estudios', 'like', '%' . $search . '%');
            $query->orWhere('diagnosticos', 'like', '%' . $search . '%');
        }

        $limit = $limit === null ? 50 : $limit;
        if ($isPaginate === true) {
            $query = $query->paginate($limit);
        } else {
            $query = $query->get();
        }
        return $query;
    }
}

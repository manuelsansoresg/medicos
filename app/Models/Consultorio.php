<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Consultorio extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey   = 'idconsultorios';
    protected $table        = 'consultorios';
    protected $fillable = [
        'idconsultorios','vnumconsultorio','thubicacion','ttelefono','idclinica'
    ];

    public static function getAll()
    {
        return Consultorio::all();
    }

    public static function getMyCon()
    {
        /* $user       = User::find(Auth::user()->id);
        $is_admin   = Auth::user()->hasRole(['medico', 'auxiliar', 'secretario']); */
        $clinica                  = Session::get('clinica');
        
        return Consultorio::where(['idclinica'=> $clinica])->get();
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $consultorio_id = $request->consultorio_id;
        if ($consultorio_id == null) {
            $consultorio = Consultorio::create($data);
        } else {
            $consultorio = Consultorio::find($consultorio_id);
            $consultorio->update($data);
        }
        return $consultorio;
    }

    public function consultorioClinica()
    {
        return $this->belongsTo(Clinica::class, 'idclinica');
    }
}

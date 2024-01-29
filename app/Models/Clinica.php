<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey   = 'idclinica';
    protected $table        = 'clinica';
    protected $fillable = [
        'idclinica','tnombre','tdireccion','vrfc','ttelefono','logotipo','rutalogo','istatus','vfolioclinica'
    ];

    public static function getAll()
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        return Clinica::all();
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $clinica_id = $request->clinica_id;
        if ($clinica_id == null) {
            $clinica = Clinica::create($data);
        } else {
            $clinica = Clinica::find($clinica_id);
            $clinica->update($data);
        }
        return $clinica;
    }

    public function consultorios()
    {
        return $this->hasMany(Consultorio::class, 'idclinica');
    }

}

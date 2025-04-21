<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Clinica extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey   = 'idclinica';
    protected $table        = 'clinica';
    protected $fillable = [
        'idclinica','tnombre','tdireccion','vrfc','ttelefono','logotipo','rutalogo','istatus','vfolioclinica', 'idusrregistra'
    ];

    public static function getAll()
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $idusrregistra = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');
        $isMedico = Auth::user()->hasRole('medico');
        $myUser = User::find(Auth::user()->id);

        if ($isAdmin) {
            return Clinica::all();
        } else {
            return Clinica::where('idusrregistra', $idusrregistra)->get();
        }
        
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $userPrincipal = User::getMyUserPrincipal();
        $data['idusrregistra'] = $userPrincipal;
        $clinica_id = $request->clinica_id;

        if ($clinica_id == null) {
            $clinica = Clinica::create($data);
            $statusPackages = Solicitud::getUsedStatusPackages();
            $solicitudId = $statusPackages['totalClinica']['solicitudId'];
            VinculacionSolicitud::saveVinculacion($clinica->idclinica, $solicitudId); 
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClinicaUser extends Model
{
    use HasFactory;

    protected $table = 'clinica_user';
    protected $primaryKey   = 'user_id';
    protected $fillable = [
        'user_id','clinica_id'
    ];

    public static function myClinics()
    {
        $is_admin   = Auth::user()->hasRole(['administrador']);
        if ($is_admin === true) {
            $my_clinics = ClinicaUser::select('clinica_id')->distinct()->get();

        } else {
            $my_clinics = ClinicaUser::where('user_id', User::getMyUserPrincipal())->get();
        }
        return $my_clinics;
    }

    public static function saveEdit($id, $request)
    {
        $existingConfiguration = ClinicaUser::where(['user_id' => $id]);

        if ($existingConfiguration != null) {
            $existingConfiguration->delete();
        }

        if (isset($request->clinicas)) {
            $clinicas = $request->clinicas;
            
            
            foreach ($clinicas as $key => $clinica) {
                $existingClinica = Clinica::find($clinica);
        
                if ($existingClinica) {
                    $data_financial = array(
                        'user_id' => $id,
                        'clinica_id' => $clinica,
                    );
        
                    ClinicaUser::create($data_financial);
                }
            }
        }
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'clinica_id');
    }
}

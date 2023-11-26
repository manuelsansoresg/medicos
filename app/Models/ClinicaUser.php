<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicaUser extends Model
{
    use HasFactory;

    protected $table = 'clinica_user';
    protected $primaryKey   = 'user_id';
    protected $fillable = [
        'user_id','clinica_id'
    ];

    public static function saveEdit($id, $request)
    {
        if (isset($request->clinicas)) {
            $clinicas = $request->clinicas;
            $existingConfiguration = ClinicaUser::where(['user_id' => $id]);

            if ($existingConfiguration != null) {
                $existingConfiguration->delete();
            }
            
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultorioUser extends Model
{
    use HasFactory;

    protected $table = 'consultorio_user';
    protected $primaryKey   = 'user_id';
    protected $fillable = [
        'user_id','consultorio_id'
    ];

    public static function saveEdit($id, $request)
    {
        $existingConfiguration = ConsultorioUser::where(['user_id' => $id]);

        if ($existingConfiguration != null) {
            $existingConfiguration->delete();
        }

        if (isset($request->consultorios)) {
            $consultorios = $request->consultorios;
            
            
            foreach ($consultorios as $key => $consultorio) {
                $existingConsultorio = Consultorio::find($consultorio);
        
                if ($existingConsultorio) {
                    $data_financial = array(
                        'user_id' => $id,
                        'consultorio_id' => $consultorio,
                    );
        
                    ConsultorioUser::create($data_financial);
                }
            }
        }
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'consultorio_id');
    }

}

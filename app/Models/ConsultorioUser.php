<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConsultorioUser extends Model
{
    use HasFactory;

    protected $table = 'consultorio_user';
    protected $primaryKey   = 'user_id';
    protected $fillable = [
        'user_id','consultorio_id'
    ];

    public static function getAll()
    {
        $userId            = User::getMyUserPrincipal();
        $is_admin   = Auth::user()->hasRole(['administrador']);
        $consultorios = ConsultorioUser::where('user_id', $userId);
        if ($is_admin === true) {
            $consultorios = ConsultorioUser::select('consultorio_id', 'user_id')->distinct()->get();
        } else {
            $consultorios = ConsultorioUser::where('user_id', $userId)->get();
        }
        return $consultorios;
    }

    public static function myConsultories()
    {
        $is_admin   = Auth::user()->hasRole(['administrador']);
        if ($is_admin === true) {
            $my_consultories = ConsultorioUser::select('consultorio_id')->distinct()->get();
            
        } else {
            $isPaciente = Auth::user()->hasRole(['paciente']);
            if ($isPaciente === true) {
                $my_consultories = UserCita::where('paciente_id', Auth::user()->id)
                    ->select('id_consultorio')
                    ->distinct()
                    ->get();
            } else {
                $my_consultories = ConsultorioUser::where('user_id', User::getMyUserPrincipal())->get();
            }
        }
        return $my_consultories;
    }

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

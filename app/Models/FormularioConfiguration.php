<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FormularioConfiguration extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'active'];

    public static function getMyTemplates()
    {
        $userPrincipal  = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');
        if ($isAdmin) {
            $myTemplate = FormularioConfiguration::where(['active' => 1])->get();
        } else {
            $myTemplate     = FormularioConfiguration::where(['user_id' => $userPrincipal, 'active' => 1])->first();
            //$myfields       = $myTemplate != null ? $myTemplate->fields : null;
        }
        return $myTemplate;
    }

    public function fields()
    {
        return $this->hasMany(FormularioField::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FormularioConfiguration extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'active'];

    public static function getAllMyTemplates($isActive = 1)
    {
        $userPrincipal  = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');
        if ($isAdmin) {
            $myTemplates = FormularioConfiguration::where('active', $isActive)->with('fields')->get();
        } else {
            $myTemplates     = FormularioConfiguration::where('active', $isActive)->where('user_id', $userPrincipal)->with('fields')->get();
        }
        return $myTemplates;
    }

    public static function getMyTemplates()
    {
        $userPrincipal  = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');
        if ($isAdmin) {
            $myTemplate = FormularioConfiguration::where(['active' => 1])->get();
        } else {
            $myTemplates     = FormularioConfiguration::where(['user_id' => $userPrincipal, 'active' => 1])->first();
            $myTemplate = $myTemplates ? [$myTemplates] : [];
        }
        return $myTemplate;
    }

    public function fields()
    {
        return $this->hasMany(FormularioField::class);
    }
}

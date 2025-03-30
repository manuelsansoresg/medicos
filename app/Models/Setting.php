<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['is_payment_card', 'is_payment_transfer', 'banco', 'titular', 'cuenta', 'clabe'];


    public static function saveEdit($request)
    {
        $data = $request->data;
        $id = $request->setting_id;
        if ($id == null ) {
            $package = Setting::create($data);
        } else {
            Package::where('id', $id)->update($data);
            $package = Setting::find($id);
        }

        
        return $package;
    }

}
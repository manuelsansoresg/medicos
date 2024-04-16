<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

    protected $table = 'access';
    protected $fillable = [
        'user_id',
        'num_doctor',
        'num_auxiliar',
        'fecha_vencimiento',
        'costo',
        'is_pagado',
        'status',
    ];

    public static function getAll()
    {
        return Access::select('name', 'user_id', 'num_doctor', 'num_auxiliar', 'fecha_vencimiento', 'costo', 'is_pagado', 'access.status')
                    ->join('users', 'users.id', 'access.user_id')
                    ->get();
    }

    public static function saveEdit($request)
    {
        $accesId = $request->acces_id;
        $data = $request->data;

        if ($accesId == null) {
            Access::create($data);
        } else {
            Access::find($accesId)->update($data);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'precio',
        'porcentaje_ganancia',
        'descripcion',
        'status',
    ];

    public static function saveEdit($request)
    {
        $data = $request->data;
        $id = $request->id;

        if ($id == null ) {
            $catalog = CatalogPrice::create($data);
        } else {
            $catalog = CatalogPrice::where('id', $id)->update($data);
        }
        return $catalog;
    }
}

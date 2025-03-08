<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'status',
        'idusrregistra',
    ];

    public static function saveEdit($request)
    {
        $data = $request->data;
        $id = $request->id;
        $elementosSeleccionados = $request->elementos ?? [];
        $elementosMaximos = $request->max ?? [];
        if ($id == null ) {
            $package = Package::create($data);
        } else {
            Package::where('id', $id)->update($data);
            $package = Package::find($id);
        }

        if ($package) {
            ItemPackage::saveItem($elementosSeleccionados, $elementosMaximos, $package);
        }
        return $package;
    }

    public function items()
    {
        return $this->hasMany(ItemPackage::class, 'package_id');
    }
}

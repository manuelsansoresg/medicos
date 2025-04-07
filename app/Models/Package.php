<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'status',
        'idusrregistra',
        'isValidateCedula',
        'tipoReporte',
    ];

    public static function getMyPackate()
    {
        $package = Package::where([
            'status'  => '1',
            
        ])->first();
        return $package;
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $id = $request->id;
        $elementosSeleccionados = $request->elementos ?? [];
        $elementosMaximos = $request->max ?? [];
        $data['idusrregistra'] = Auth::user()->id;
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

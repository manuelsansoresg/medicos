<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPackage extends Model
{
    use HasFactory;
    protected $table = 'item_packages';
    protected $fillable = [
        'package_id',
        'catalog_price_id',
        'max',
    ];

    public static function saveItem($elementosSeleccionados, $elementosMaximos, $package)
    {
        // Eliminar registros existentes para este paquete
        self::where('package_id', $package->id)->delete();
        
        // Crear nuevos registros con los valores seleccionados y sus máximos
        foreach ($elementosSeleccionados as $elementoId) {
            self::create([
                'package_id' => $package->id,
                'catalog_price_id' => $elementoId,
                'max' => isset($elementosMaximos[$elementoId]) ? $elementosMaximos[$elementoId] : null
            ]);
        }
    }

    public function catalogPrice()
    {
        return $this->belongsTo(CatalogPrice::class, 'catalog_price_id');
    }

    public function items()
    {
        return $this->hasMany(ItemPackage::class);
    }
}

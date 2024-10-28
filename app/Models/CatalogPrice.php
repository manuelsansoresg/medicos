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
    ];
}

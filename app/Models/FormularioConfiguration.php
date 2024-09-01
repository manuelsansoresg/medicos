<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioConfiguration extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'active'];

    public function fields()
    {
        return $this->hasMany(FormularioField::class);
    }
}

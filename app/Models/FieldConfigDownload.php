<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldConfigDownload extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',	'idusrregistra',	'formulario_field_id',	'is_download',
    ];
}

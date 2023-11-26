<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey   = 'idconsultorios';
    protected $table        = 'consultorios';
    protected $fillable = [
        'idconsultorios','vnumconsultorio','thubicacion','ttelefono','idclinica'
    ];

    public static function getAll()
    {
        return Consultorio::all();
    }
}

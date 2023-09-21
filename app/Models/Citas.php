<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    use HasFactory;
    protected $table = 'citas';
    protected $fillable = [
        'idcitas','vfoliopaciente','tmotivoconsulta','dfecha','vhora','idregistrado','iddoctor','idclinica','idconsultorio','idcliente','istatuscita','idiaconsulta','idiasemana','iformacita'
    ];
}

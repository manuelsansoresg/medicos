<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VinculoPacienteUsuario extends Model
{
    use HasFactory;
    protected $table = 'vinculo_paciente_usuarios';
    protected $fillable = ['user_id', 'paciente_id', 'is_link_by_curp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}

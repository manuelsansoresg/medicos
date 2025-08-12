<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienComun extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'hour',
        'description',
        'user_id',
        'idusrregistra',
        'status',
    ];
    protected $table = 'bien_comun';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userRegistra()
    {
        return $this->belongsTo(User::class, 'idusrregistra');
    }
}

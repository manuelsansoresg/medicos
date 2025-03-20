<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_token_id',
        'user_id',
        'paquete_id',
        'status',
        'amount',
        'currency',
        'description',
    ];
}

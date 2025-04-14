<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSystem extends Model
{
    use HasFactory;
    protected $table = 'log_system';
    protected $fillable = ['user_id', 'action', 'description'];

    public static function createLog($user_id, $action, $description)
    {
        self::create([
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description   
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

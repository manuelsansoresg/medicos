<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'user_id',
        'idRel',
        'respuesta_id',
        'type',
    ];

    public static function commentSolicitud($solicitudId, $request)
    {
        $data            = $request->data;
        $commentId = $request->commentId;

        $data['user_id'] = Auth::user()->id;
        $data['type']    = 2; //comentario nuevo
        $data['idRel']   = $solicitudId;
        if ($commentId != null) {
            $data['respuesta_id'] = $commentId;
            $data['type']    = 3; //tipo respuesta 
        }
        $comment = Comment::create($data);
        return $comment;
    }
}

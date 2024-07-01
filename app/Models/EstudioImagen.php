<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   EstudioImagen extends Model
{
    use HasFactory;
    protected $table = 'estudio_imagenes';
    protected $fillable = [
        'estudio_id',
        'image',
    ];
    const PATH = 'image/estudios';

    public static function saveImage($request)
    {
        if ($request->hasFile('file') != false) {
            $document   = $request->file('file');
            $name_full  = rand(1, 999).'-'.$document->getClientOriginalName();
            $path       = EstudioImagen::PATH.'/';

            if ($document->move($path, $name_full)) {
                $data = array(
                    'image' => $name_full,
                    'estudio_id' => $request->estudioId,
                );
                
                EstudioImagen::create($data);
            }
        }
    }

    public static function deleteImage($imageId)
    {
        $image = EstudioImagen::find($imageId);
        unlink(EstudioImagen::PATH.'/'.$image->image);
        $image->delete();
    }
}

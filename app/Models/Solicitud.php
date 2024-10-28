<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Solicitud extends Model
{
    use HasFactory;
    protected $fillable = [
        'catalog_prices_id',
        'comprobante',
        'estatus',
        'cantidad',
        'user_id'
    ];

    protected $table = 'solicitudes';

    public static function getAll()
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            return Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')->get();
        } else {
            return Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')->where('user_id', $user_id)->get();
        }
        
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $solicitudId = $request->solicitudId;
        if ($solicitudId == null) {
            $data['user_id'] = Auth::user()->id;
            $solicitud = new Solicitud($data);
            $solicitud->save();
        } else {
            $solicitud = Solicitud::where('id', $solicitudId)->update($data);
            
        }
        return $solicitud;
    }
}

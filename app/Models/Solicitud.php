<?php

namespace App\Models;

use DateTime;
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
        'user_id',
        'fecha_vencimiento',
    ];

    protected $table = 'solicitudes';

    public static function getAll()
    {
        //*cambiar listando las clinicas donde perteneces si no eres administrador
        $user_id = User::getMyUserPrincipal();
        $isAdmin = Auth::user()->hasRole('administrador');

        if ($isAdmin) {
            return Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at', 'solicitudes.updated_at', 'name', 'vapellido'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->get();
        } else {
            return Solicitud::select(
                'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'solicitudes.estatus', 'solicitudes.created_at', 'name', 'vapellido'
                )->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                ->join('users', 'users.id', 'solicitudes.user_id')
                ->where('user_id', $user_id)->get();
        }
        
    }

    public static function getPaqueteActivo($solicitud)
    {
        $userId[]     = $solicitud->user_id;
        $getUser      = User::find($solicitud->user_id);
        $userId[]     = $getUser->usuario_principal;
        $getSolicitud = Solicitud::where(function($query) use ($userId) {
                        foreach ($userId as $id) {
                            $query->orWhere('user_id', $id);
                        }
                    })
                    ->where('catalog_prices_id', 1)
                    ->where('estatus', 1)
                    ->first();
        $price  = 0;
        if ($getSolicitud != null) {
            // Asumiendo que $fechaVencimiento contiene la fecha de vencimiento en formato 'Y-m-d'
            $fechaVencimiento = new DateTime('2025-10-01');
            $fechaActual = new DateTime('2024-11-01'); // Suponiendo que esta es la fecha del servidor

            // Calcula la diferencia entre la fecha actual y la fecha de vencimiento
            $diferencia = $fechaActual->diff($fechaVencimiento);

            // Obtiene el total de meses entre la fecha actual y la fecha de vencimiento
            $mesesTranscurridos = ($diferencia->y * 12) + $diferencia->m;

            // Calcula cuántos meses faltan para completar 12
            $mesesRestantesParaCompletarDoce = 12 - $mesesTranscurridos;
            if ($mesesRestantesParaCompletarDoce > 0 ) {
                $getPrice = CatalogPrice::find($getSolicitud->catalog_prices_id);
                $precioPaquete = $getPrice->precio / 12;
                $price = $precioPaquete * $mesesRestantesParaCompletarDoce;
            }
            
        }

        return $price;
        
    }

    public static function saveEdit($request)
    {
        $data = $request->data;
        $solicitudId = $request->solicitudId;

        // Verificamos si el usuario tiene el rol de administrador, médico o auxiliar.
        $isAdmin = Auth::user()->hasRole('administrador');
        $isMedico = Auth::user()->hasRole('medico');
        $isAuxiliar = Auth::user()->hasRole('auxiliar');
        $isExistAcceso = $isAdmin === true ? false : true;
        $solicitud = null;
        $errorMessage = '';

        // Validación solo si el usuario no es admin.
        if ($isMedico || $isAuxiliar) {
            $user_id = User::getMyUserPrincipal();

            // Verificamos si el usuario ya tiene una solicitud de paquete básico pendiente.
            $getSolicitudPendiente = Solicitud::where('user_id', $user_id)
                                            ->where('estatus', 0)
                                            ->first();

            // Si ya hay una solicitud pendiente de activación
            if ($getSolicitudPendiente) {
                $isExistAcceso = true;
                $errorMessage = 'Ya tienes una solicitud pendiente en espera de activación.';
            } else {
                // Verificamos si el usuario ya tiene un paquete básico activo.
                $getSolicitudBasico = Solicitud::where('user_id', $user_id)
                                            ->where('catalog_prices_id', 1)
                                            ->where('estatus', 1)
                                            ->first();

                if ($getSolicitudBasico != null && $data['catalog_prices_id'] == 1) {
                    $isExistAcceso = true;
                    $errorMessage = 'Solo puedes solicitar 1 paquete básico activo.';
                } elseif ($getSolicitudBasico == null && $data['catalog_prices_id'] != 1) {
                    // Si el usuario quiere solicitar un paquete que no es el básico, pero no tiene uno básico activo.
                    $isExistAcceso = true;
                    $errorMessage = 'Para solicitar este paquete debes tener un paquete básico activo.';
                } else {
                    $isExistAcceso = false;
                }
            }
        }

        // Si la validación falla, enviamos el mensaje de error.
        if ($isExistAcceso == false) {
            if ($solicitudId == null) {
                $data['user_id'] = Auth::user()->id;
                $solicitud = new Solicitud($data);
                $solicitud->save();
            } else {
                $solicitud = Solicitud::where('id', $solicitudId)->update($data);
            }
        }

        return array('solicitud' => $solicitud, 'isReturnError' => $isExistAcceso, 'errorMessage' => $errorMessage);
    }
}

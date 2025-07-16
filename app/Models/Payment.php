<?php

namespace App\Models;

use App\Lib\PayClipService;
use App\Models\VinculacionSolicitud;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_token_id',
        'solicitud_id',
        'user_id',
        'status',
        'amount',
        'currency',
        'description',
    ];

    public static function savePayment($dataPayment)
    {
        try {
            Log::info('Iniciando Payment::savePayment con datos:', $dataPayment);
            $user = User::find($dataPayment['user_id']);
            $payment = new PayClipService();
            Log::info('PayClipService creado, procesando pago...');
            
            $getPayment = $payment->processPayment($dataPayment['amount'], $dataPayment['card_token_id'], $user->email, $user->ttelefono, $dataPayment['description']);
            Log::info('Respuesta de PayClip:', $getPayment);
            
            $status = $getPayment['status'] == 'approved' ? 1 : 0;
            Log::info('Status del pago:', ['status' => $status, 'original_status' => $getPayment['status']]);
            
            if ($status == 1) {
                Log::info('Pago aprobado, obteniendo status packages...');
                $statusPackages = Solicitud::getUsedStatusPackages($user->id);
                Log::info('Status packages obtenido:', $statusPackages);
                
                if ($statusPackages && isset($statusPackages['totalUsuariosSistema']['solicitudId'])) {
                    $solicitudId = $statusPackages['totalUsuariosSistema']['solicitudId'];
                    Log::info('Guardando vinculación con solicitudId:', ['solicitudId' => $solicitudId, 'user_id' => $dataPayment['user_id']]);
                    VinculacionSolicitud::saveVinculacion($dataPayment['user_id'], $solicitudId); 
                } else {
                    Log::warning('No se pudo obtener solicitudId para vinculación');
                }
            }
            
            Log::info('Creando registro de Payment...');
            $payment = Payment::create([
                'card_token_id' => $dataPayment['card_token_id'],
                'solicitud_id' => $dataPayment['solicitud_id'],
                'user_id' => $dataPayment['user_id'],
                'status' => $status,
                'amount' => $dataPayment['amount'],
                'currency' => 'MXN',
                'description' => $dataPayment['description'],
            ]);
            
            Log::info('Payment creado exitosamente:', $payment->toArray());
            return $getPayment;
        } catch (\Exception $e) {
            Log::error('Error en Payment::savePayment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}

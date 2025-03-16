<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClipPaymentController extends Controller
{
     /**
     * Muestra la página del generador de enlaces de pago
     */
    public function showPaymentLinkGenerator()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://api.payclip.com/v2/checkout', [
            'body' => '{"amount":190,"currency":"MXN","purchase_description":"paquete basico","redirection_url":{"success":"https://my-website.com/redirection/success?external_reference=OID123456789","error":"https://my-website.com/redirection/error?external_reference=OID123456789","default":"https://my-website.com/redirection/default"}}',
            'headers' => [
              'Authorization' => 'dGVzdF8xOWEzYzFlNS01M2UwLTRhMmItOGZhOS0zODMyMTE1YmJkYWU6YmUyNmVkZmMtMWU2Zi00YmFmLTg1YTgtZDkyMmVmOGY4YjAz',
              'accept' => 'application/json',
              'content-type' => 'application/json',
            ],
          ]);

        // Puedes pasar datos adicionales a la vista si es necesario
        $accessLevels = [
            'basic' => 'Básico',
            'standard' => 'Estándar',
            'premium' => 'Premium',
            'enterprise' => 'Empresarial'
        ];
        
        return view('developer.payment-link-generator', [
            'accessLevels' => $accessLevels,
            'response' => $response,
        ]);
    }
    
    /**
     * Maneja los webhooks entrantes de Clip
     */
    public function handleClipWebhook(Request $request)
    {
        // Obtener los datos del webhook
        $payload = $request->all();
        
        // Registrar el webhook para depuración
        Log::info('Webhook de Clip recibido', $payload);
        
        // Verificar si es un pago exitoso
        if (isset($payload['status']) && ($payload['status'] === 'paid' || $payload['status'] === 'completed')) {
            // Procesar el pago exitoso
            $metadata = $payload['metadata'] ?? [];
            $developerId = $metadata['developer_id'] ?? null;
            $accessLevel = $metadata['access_level'] ?? null;
            $expirationDays = isset($metadata['expiration_days']) ? (int)$metadata['expiration_days'] : 30;
            
            if ($developerId) {
                // Aquí implementarías la lógica para activar el acceso del desarrollador
                // Por ejemplo, actualizando su registro en la base de datos
                $this->activateDeveloperAccess($developerId, $accessLevel, $expirationDays, $payload['id'] ?? null);
            }
        }
        
        // Devolver una respuesta 200 para confirmar la recepción del webhook
        return response()->json(['received' => true]);
    }
    
    /**
     * Activa el acceso para un desarrollador
     * (Método privado para implementar la lógica específica de tu aplicación)
     */
    private function activateDeveloperAccess($developerId, $accessLevel, $expirationDays, $paymentId)
    {
        // Aquí implementarías la lógica para activar el acceso
        // Por ejemplo:
        
        Log::info("Activando acceso para desarrollador: {$developerId}", [
            'access_level' => $accessLevel,
            'expiration_days' => $expirationDays,
            'payment_id' => $paymentId
        ]);
        
        // Ejemplo de posible implementación:
        // $developer = Developer::findOrFail($developerId);
        // $developer->access_level = $accessLevel;
        // $developer->access_expiration = now()->addDays($expirationDays);
        // $developer->last_payment_id = $paymentId;
        // $developer->save();
    }
}

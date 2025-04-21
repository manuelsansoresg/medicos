<?php

namespace App\Lib;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PayClipService
{
    protected string $apiUrl;
    protected string $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.payclip.api_url', 'https://api.payclip.com');
        $this->authToken = env('PAYCLIP_AUTH_TOKEN');
    }

    /**
     * Procesa un pago mediante PayClip
     * 
     * @param float $amount Monto del pago
     * @param string $currency Moneda (por defecto MXN)
     * @param string $description Descripción del pago
     * @param string $paymentToken Token del método de pago
     * @param string $email Correo electrónico del cliente
     * @param string $phone Teléfono del cliente
     * @return array Respuesta de la API de PayClip
     */
    public function processPayment(
        float $amount,
        string $paymentToken,
        string $email,
        string $phone,
        string $description = 'Pago de servicio',
        string $currency = 'MXN'
    ) {
        //dd($this->authToken);
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->authToken,
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payments", [
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'payment_method' => [
                'token' => $paymentToken
            ],
            'customer' => [
                'email' => $email,
                'phone' => $phone
            ]
        ]);
        $responseData = $response->json();
        $status = $responseData['status'] ?? null;
        return [
            'response' => $responseData,
            'status' => $status
        ];
    }
}
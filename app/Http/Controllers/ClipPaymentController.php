<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClipPaymentController extends Controller
{
    protected $clipApiUrl = 'https://api.payclip.com/v2';
    
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('CLIP_API_KEY');
    }

    /**
     * Muestra la vista para iniciar el pago con Clip
     */
    public function showPaymentForm()
    {
        return view('payments.clip-payment');
    }

    /**
     * Inicia una transacción con Clip
     */
    public function createPayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
                'Content-Type' => 'application/json',
            ])->post($this->clipApiUrl . '/charges', [
                'amount' => $validated['amount'] * 100, // Clip espera el monto en centavos
                'currency' => 'MXN',
                'description' => $validated['description'],
                'customer' => [
                    'email' => $validated['email'],
                    'name' => $validated['name'],
                ],
                'return_url' => route('clip.payment.callback'),
                'cancel_url' => route('clip.payment.cancel'),
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                // Guardar información de la transacción en la base de datos si es necesario
                // ...

                // Redirigir al usuario a la página de pago de Clip
                return redirect($responseData['payment_url']);
            } else {
                return back()->withErrors(['message' => 'Error al procesar el pago: ' . ($responseData['message'] ?? 'Error desconocido')]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error al conectar con Clip: ' . $e->getMessage()]);
        }
    }

    /**
     * Callback para procesar la respuesta de pago exitoso de Clip
     */
    public function handleCallback(Request $request)
    {
        // Verificar la transacción con Clip
        $paymentId = $request->input('payment_id');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
            ])->get($this->clipApiUrl . '/charges/' . $paymentId);

            $paymentData = $response->json();

            if ($response->successful() && isset($paymentData['status']) && $paymentData['status'] === 'paid') {
                // Actualizar el estado del pago en la base de datos
                // ...

                return view('payments.success', ['paymentData' => $paymentData]);
            } else {
                return view('payments.failed', ['error' => 'La verificación del pago falló']);
            }
        } catch (\Exception $e) {
            return view('payments.failed', ['error' => 'Error al verificar el pago: ' . $e->getMessage()]);
        }
    }

    /**
     * Maneja la cancelación del pago por parte del usuario
     */
    public function handleCancellation()
    {
        return view('payments.cancelled');
    }
}

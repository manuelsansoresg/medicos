<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\NotificationUser;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function storeTransfer(Request $request)
    {
        
        $userId = $request->user_id;
        $paqueteId = $request->paquete_id;
        $package = Package::find($paqueteId);
        $amount = $package->precio;
        //crear solicitud de registro
        $solicitud = Solicitud::create([
            'solicitud_origin_id' => $package->id,
            'source_id' => 0,
            'estatus' => 0,
            'cantidad' => 1,
            'precio_total' => $amount,
            'user_id' => $userId,
            'payment_type' => 'transferencia',
        ]);
        $notification = new NotificationUser();
        $notification->requestRegistration($userId, $solicitud->id, 'transfer');
        
        Notification::SolicitudPaqueteByTransfer($package->id, $userId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Log::info('PaymentController::store iniciado con request:', $request->all());
            
            $package = Package::find($request->paquete_id);
            if (!$package) {
                Log::error('Paquete no encontrado:', ['paquete_id' => $request->paquete_id]);
                return response()->json(['error' => 'Paquete no encontrado'], 404);
            }
            
            $amount = $package->precio;
            $description = 'Pago de '.$package->nombre;
            $userId = $request->user_id;
            
            Log::info('Datos del paquete:', ['package' => $package->toArray(), 'amount' => $amount, 'userId' => $userId]);
            
            $validatedData = $request->validate([
                'card_token_id' => 'required|string',
                'paquete_id' => 'required|integer',
                'user_id' => 'required|integer',
                'description' => 'nullable|string',
            ]);
            
            Log::info('Datos validados:', $validatedData);
            
            //crear solicitud de registro
            $solicitud = Solicitud::create([
                'solicitud_origin_id' => $package->id,
                'source_id' => 0,
                'estatus' => 1,
                'cantidad' => 1,
                'precio_total' => $amount,
                'user_id' => $userId,
                'payment_type' => 'tarjeta de crédito',
            ]);
            
            Log::info('Solicitud creada:', $solicitud->toArray());
            
            $dataPayment = array(
                'card_token_id' => $validatedData['card_token_id'],
                'solicitud_id' => $solicitud->id,
                'user_id' => $userId,
                'amount' => $amount,
                'description' => $description,
            );
            
            Log::info('Llamando a Payment::savePayment con datos:', $dataPayment);
            $payment = Payment::savePayment($dataPayment, $solicitud->id);
            Log::info('Payment::savePayment completado:', $payment);

            $notification = new NotificationUser();
            $notification->requestRegistration($userId, $solicitud->id);
            
            User::where('id', $userId)->update(['status' => 1]);

            Notification::SolicitudPaquete($package->id, $userId);
            
            Log::info('PaymentController::store completado exitosamente');
            return response()->json($payment, 201);
            
        } catch (\Exception $e) {
            Log::error('Error en PaymentController::store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

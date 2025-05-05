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
            'source_id' => 1,
            'estatus' => 0,
            'cantidad' => 1,
            'precio_total' => $amount,
            'user_id' => $userId,
            'payment_type' => 'transferencia',
        ]);

        $notification = new NotificationUser();
        $notification->requestRegistration($userId, $solicitud->id, 'transfer');

        $user = User::find($userId);

       Notification::SolicitudPaquete($package->id, $userId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = Package::find($request->paquete_id);
        $amount = $package->precio;
        $description = 'Pago de '.$package->nombre;
        $userId = session('user_id');
        $validatedData = $request->validate([
            'card_token_id' => 'required|string',
            'paquete_id' => 'required|integer',
            'description' => 'nullable|string',
        ]);
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
        
        $dataPayment = array(
            'card_token_id' => $validatedData['card_token_id'],
            'solicitud_id' => $solicitud->id,
            'user_id' => $userId,
            'amount' => $amount,
            'description' => $description,
        );
        $payment = Payment::savePayment($dataPayment);

        $notification = new NotificationUser();
        $notification->requestRegistration($userId, $solicitud->id);
        
        User::where('id', $userId)->update(['status' => 1]);


        Notification::SolicitudPaquete($package->id, $userId);
        
        return response()->json($payment, 201);
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

<?php

namespace App\Models;

use App\Lib\PayClipService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $payment = new PayClipService();
        $getPayment = $payment->processPayment(100, $dataPayment['card_token_id'], 'manuelsansoresg@gmail.com', '9991575581');
        $status = $getPayment['status'] ==  'approved'? 1 : 0;
        $payment = Payment::create([
            'card_token_id' => $dataPayment['card_token_id'],
            'solicitud_id' => $dataPayment['solicitud_id'],
            'user_id' => $dataPayment['user_id'],
            'status' => $status,
            'amount' => $dataPayment['amount'],
            'currency' => 'MXN',
            'description' => $dataPayment['description'],
        ]);
        
      
        return $getPayment;
    }
}

@extends('layouts.template_email')

@section('content')
<center style="width: 100%; background-color: #f5f6fa;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
        <tr>
           <td style="padding: 40px 0;">
                <table style="width:100%;max-width:620px;margin:0 auto;">
                    <tbody>
                        <tr>
                            <td style="text-align:center;padding-bottom:25px">
                                <p style="font-size: 14px; color: #6576ff; padding-top: 12px;">
                                   Proceso de pago
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
                    <tbody>
                        <tr>
                            <td style="padding: 30px 30px 20px">
                                <p style="margin-bottom: 10px;"><strong>Hola {{ $nombre }}</strong>,</p>
                                @if($paymentMethod == 'card')
                                <p style="margin-bottom: 10px;">
                                    <b>Estatus:</b> Pago registrado
                                </p>
                                @else
                                <p style="margin-bottom: 10px;">
                                    <b>Estatus:</b> Pago pendiente
                                </p>
                                @endif
                                @if($paymentMethod == 'card')
                                <p style="margin-bottom: 15px;">
                                 El pago se ha registrado correctamente por la cantidad de: <b>${{ format_price($total) }}</b>
                                </p>
                                @else
                                <p style="margin-bottom: 15px;">
                                    Para completar la activación del sistema, realice la transferencia correspondiente por la cantidad de: <b>${{ format_price($total) }}</b>  y darle click al botón de adjuntar comprobante de pago.
                                </p>
                                @endif
                                @if($paymentMethod == 'card')
                                <p style="margin-bottom: 15px;">
                                    Por favor, haga clic en el siguiente enlace para iniciar sesion: <a href="{{ env('APP_URL') }}/login" style="color: #3366cc; font-weight: bold;">Continuar con mi solicitud</a>
                                </p>
                                @else
                                <div class="alert alert-info text-left">
                                    <h6><i class="fas fa-university"></i> Datos para Transferencia Bancaria</h6>
                                    <p class="mb-1"><strong>Banco:</strong> {{ $setting->banco }}</p>
                                    <p class="mb-1"><strong>Titular:</strong> {{ $setting->titular }}</p>
                                    <p class="mb-1"><strong>Cuenta:</strong> {{ $setting->cuenta }}</p>
                                    <p class="mb-1"><strong>CLABE:</strong> {{ $setting->clabe }}</p>
                                    <a href="{{ env('APP_URL') }}/solicitud/{{ $solicitud->id }}/comprobante/adjuntar" style="color: #3366cc; font-weight: bold;">Adjuntar comprobante de pago</a>
                                </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
               
           </td>
        </tr>
    </table>
</center>
@endsection
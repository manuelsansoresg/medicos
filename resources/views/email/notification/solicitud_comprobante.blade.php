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
                                   Proceso solicitud
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
                                <p style="margin-bottom: 10px;">
                                    <b> Estatus:</b> comprobación de información válida
                                 </p>
                                 <p style="margin-bottom: 10px;">
                                     Favor de realizar una transferencia utilizando los datos proporcionados, iniciar sesión en el siguiente enlace y adjuntar el comprobante de pago <a href="{{ env('APP_URL') }}/admin/solicitudes/{{ $solicitudId }}">Adjuntar comprobante</a>
                                 </p>
                                 <p style="margin-bottom: 10px;">

                                     <b>NOMBRE:</b> JOSE VAZQUEZ
                                     <br>
                                     <b>CLABE:</b> 0123348458585858
                                     <br>
                                     <b>BANCO:</b> BANAMEX
                                     <br>
                                     <b>CANTIDAD A DEPOSITAR:</b> ${{ format_price($total) }}
                                 </p>

                            </td>
                        </tr>
                    </tbody>
                </table>
               
           </td>
        </tr>
    </table>
</center>
@endsection
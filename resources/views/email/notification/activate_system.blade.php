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
                                    <b> Estatus:</b> {{ $solicitud->source_id == 0 ? 'Activación del sistema' : 'Activación' }}
                                 </p>
                                 <p style="margin-bottom: 10px;">
                                    @if ($solicitud->source_id == 0)
                                        Su paquete {{ $solicitud->nombre_solicitud }}  ha sido activado  <a href="{{ env('APP_URL') }}" class="btn-primary"> Acceder </a>
                                    @else
                                        La solicitud {{ $solicitud->nombre_solicitud }} extra ha sido activada 
                                    @endif
                                 
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
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
                                    <b> Estatus:</b> Activación del sistema
                                   {{--  @if ($solicitud->nombre === 'Paquete básico')
                                    @endif
                                    
                                    @if ($solicitud->nombre === 'Usuario extra')
                                        <b> Estatus:</b> Usuario extra activado
                                    @endif
                                    @if ($solicitud->nombre === 'consultorio extra')
                                        <b> Estatus:</b> Consultorio extra activado
                                    @endif
                                    
                                    @if ($solicitud->nombre === 'Paciente')
                                        <b> Estatus:</b> Consultorio extra activado
                                    @endif --}}
                                    
                                 </p>
                                 <p style="margin-bottom: 10px;">
                                 Su acceso al sistema ha sido activado <a href="{{ env('APP_URL') }}" class="btn-primary"> Acceder </a>
                                  {{--   @if ($solicitud->nombre === 'Paquete básico')
                                        Su acceso al sistema ha sido activado <a href="{{ env('APP_URL') }}" class="btn-primary"> Acceder </a>
                                    @endif
                                    @if ($solicitud->nombre === 'Usuario extra')
                                        Se ha activado la compra de {{ $solicitud->cantidad  }} usuario(s) extra
                                    @endif
                                    @if ($solicitud->nombre === 'consultorio extra')
                                        Se ha activado la compra de {{ $solicitud->cantidad  }} consultorio(s) extra
                                    @endif
                                    @if ($solicitud->nombre === 'Paciente')
                                        Se ha activado la compra de {{ $solicitud->cantidad  }} paciente(s) extra
                                    @endif --}}
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
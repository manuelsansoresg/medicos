@extends('layouts.template_legend')

@section('content')
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg" style="max-width: 500px;">
        <div class="card-body text-center">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="currentColor" class="bi bi-hourglass-split text-warning" viewBox="0 0 16 16">
                    <path d="M2.5 15.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zM3 14V2h10v12H3zm7.5-11h-5V4a4.5 4.5 0 0 0 2 3.732A4.5 4.5 0 0 0 10.5 4V3z"/>
                    <path d="M6 11.268A4.5 4.5 0 0 0 7.5 12h1a4.5 4.5 0 0 0 1.5-1.732A4.5 4.5 0 0 0 10 8.268V6.732A4.5 4.5 0 0 0 7.5 8H7a4.5 4.5 0 0 0-1 .732V11.268z"/>
                </svg>
            </div>
            <h2 class="card-title mb-3">Verificación en Proceso </h2>
            <p class="card-text text-muted">Estamos revisando sus datos. Le responderemos a la brevedad posible para que pueda continuar con el proceso de activación del sistema. Agradecemos su paciencia. 
              <br>  Por correo se le notificará con una URL para darle continuidad al proceso de activación.</p>
            
        </div>
    </div>
</div>
@stop
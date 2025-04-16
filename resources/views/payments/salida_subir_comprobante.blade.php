<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .success-card {
            max-width: 600px;
            width: 90%;
            padding: 40px;
            text-align: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
@inject('setting', 'App\Models\Setting')
@php
    $setting = $setting->find(1);
@endphp
<body>
    <div class="success-container">
        <div class="success-card">
            <i class="fas fa-check-circle success-icon"></i>
            <h2 class="mb-4">¡Comprobante adjuntado correctamente!</h2>
            <p class="lead mb-4">
                Hemos recibido su comprobante de pago correctamente. Nuestro equipo verificará la información y activará su cuenta en las próximas 24 horas.
            </p>
            <p class="text-muted">
                Le enviaremos un correo de confirmación a <strong>{{ $user->email }}</strong> una vez completado el proceso.
            </p>
            <div class="mt-4">
                <a href="/" class="btn btn-primary me-2">
                    <i class="fas fa-home me-1"></i> Volver al Inicio
                </a>
                {{-- <a href="/contact" class="btn btn-outline-secondary">
                    <i class="fas fa-question-circle me-1"></i> ¿Necesita Ayuda?
                </a> --}}
            </div>
        </div>
    </div>
    
</body>
</html> 
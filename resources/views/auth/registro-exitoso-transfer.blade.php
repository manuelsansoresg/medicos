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
            <h2 class="mb-4">¡Registro Exitoso!</h2>
            <p class="lead mb-4">
                Para completar la activación del sistema, realice la transferencia correspondiente y revise su correo electrónico. 
                En el correo encontrará las instrucciones para subir el comprobante de pago y continuar con la validación.
            </p>
            <div class="alert alert-info text-left">
                <h6><i class="fas fa-university"></i> Datos para Transferencia Bancaria</h6>
                <p class="mb-1"><strong>Banco:</strong> {{ $setting->banco }}</p>
                <p class="mb-1"><strong>Titular:</strong> {{ $setting->titular }}</p>
                <p class="mb-1"><strong>Cuenta:</strong> {{ $setting->cuenta }}</p>
                <p class="mb-1"><strong>CLABE:</strong> {{ $setting->clabe }}</p>
            </div>
        </div>
    </div>
    
</body>
</html> 
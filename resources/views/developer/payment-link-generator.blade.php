<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Doctores</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome 5.15.4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/principal.css') }}">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center mb-0">Generador de Enlaces de Pago - Clip</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="apiKey">API Key de Clip:</label>
                            <input type="password" class="form-control" id="apiKey" placeholder="Ingresa tu API Key">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="amount">Monto (MXN):</label>
                            <input type="number" class="form-control" id="amount" placeholder="Ejemplo: 299.99" step="0.01" min="1">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">Descripción:</label>
                            <input type="text" class="form-control" id="description" placeholder="Descripción del pago">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="accessLevel">Nivel de Acceso:</label>
                            <select class="form-control" id="accessLevel">
                                @foreach($accessLevels as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="expirationDays">Días de validez:</label>
                            <input type="number" class="form-control" id="expirationDays" value="30" min="1" max="365">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="successUrl">URL de retorno (éxito):</label>
                            <input type="url" class="form-control" id="successUrl" placeholder="https://tudominio.com/gracias" value="{{ route('developer.payment-links') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="webhookUrl">URL de webhook (opcional):</label>
                            <input type="url" class="form-control" id="webhookUrl" placeholder="https://tudominio.com/webhook" value="{{ route('webhooks.clip') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="developerId">ID del Desarrollador:</label>
                            <input type="text" class="form-control" id="developerId" placeholder="dev_12345">
                        </div>
                        
                        <button id="generateBtn" class="btn btn-primary w-100 mt-3">Generar Enlace de Pago</button>
                        
                        <div class="alert alert-danger mt-3" id="errorMessage" style="display: none;"></div>
                        
                        <div class="mt-4 p-3 bg-light rounded" id="resultContainer" style="display: none;">
                            <h4>Enlace de Pago Generado</h4>
                            <div class="p-3 bg-white rounded border mt-2 mb-3" id="paymentLink" style="word-break: break-all;"></div>
                            <p>ID del enlace: <strong id="paymentId"></strong></p>
                            <p>Expira el: <strong id="expiresAt"></strong></p>
                            <button class="btn btn-success w-100" id="copyBtn">Copiar Enlace</button>
                        </div>
                        <div class="col-12">
                            {{ $response }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap & jQuery JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    
    <script>
       const options = {
  method: 'POST',
  headers: {
    accept: 'application/json',
    'content-type': 'application/json',
    Authorization: 'dGVzdF8xOWEzYzFlNS01M2UwLTRhMmItOGZhOS0zODMyMTE1YmJkYWU6YmUyNmVkZmMtMWU2Zi00YmFmLTg1YTgtZDkyMmVmOGY4YjAz'
  },
  body: JSON.stringify({
    amount: 190,
    currency: 'MXN',
    purchase_description: 'paquete basico',
    redirection_url: {
      success: 'https://my-website.com/redirection/success?external_reference=OID123456789',
      error: 'https://my-website.com/redirection/error?external_reference=OID123456789',
      default: 'https://my-website.com/redirection/default'
    }
  })
};

fetch('https://api.payclip.com/v2/checkout', options)
  .then(response => {
    if (!response.ok) {
      return response.json().then(errorData => {
        console.error('Error de respuesta:', {
          status: response.status,
          statusText: response.statusText,
          data: errorData
        });
        throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
      });
    }
    return response.json();
  })
  .then(data => {
    console.log('Respuesta exitosa:', data);
  })
  .catch(err => {
    console.error('Error en la petición:', err.message);
    console.error('Detalles completos del error:', err);
  });
        </script>
</body>
</html>



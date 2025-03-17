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
                    <div class="card-header">Pago con Clip</div>
    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
    
                        <form method="POST" action="{{ route('clip.payment.create') }}" id="payment-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="amount">Monto (MXN)</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" required>
                            </div>
    
                            <div class="form-group mb-3">
                                <label for="description">Descripción</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
    
                            <div class="form-group mb-3">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
    
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
    
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-credit-card mr-2"></i> Pagar con Clip
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap & jQuery JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/principal.js') }}"></script>
    
    <script>
      const options = {
  method: 'POST',
  headers: {
    accept: 'application/json',
    'content-type': 'application/json',
    Authorization: 'dGVzdF8xOWEzYzFlNS01M2UwLTRhMmItOGZhOS0zODMyMTE1YmJkYWU6YmUyNmVkZmMtMWU2Zi00YmFmLTg1YTgtZDkyMmVmOGY4YjAz'
  },
  body: JSON.stringify({
    amount: 100.5,
    currency: 'MXN',
    purchase_description: 'ejemplo de compra',
    redirection_url: {
      success: 'https://my-website.com/redirection/success?external_reference=OID123456789',
      error: 'https://my-website.com/redirection/error?external_reference=OID123456789',
      default: 'https://my-website.com/redirection/default'
    }
  })
};

fetch('https://api.payclip.com/v2/checkout', options)
  .then(res => res.json())
  .then(res => console.log(res))
  .catch(err => console.error(err));
        </script>
</body>
</html>



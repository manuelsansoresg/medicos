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
    <div class="registration-container">
        <div class="header">
            <h1><i class="fas fa-user-md"></i> Registro de Doctores</h1>
            <p>Complete los siguientes pasos para registrarse en nuestra plataforma médica</p>
        </div>
        
        <div class="steps">
            <div class="step active" id="step1-indicator">
                <div class="step-number">1</div>
                <div class="step-title">Tipo de Registro</div>
                <div class="step-connector"></div>
            </div>
            <div class="step" id="step2-indicator">
                <div class="step-number">2</div>
                <div class="step-title">Selección de Paquete</div>
                <div class="step-connector"></div>
            </div>
            <div class="step" id="step3-indicator">
                <div class="step-number">3</div>
                <div class="step-title">Información Personal</div>
                <div class="step-connector"></div>
            </div>
            <div class="step" id="step4-indicator">
                <div class="step-number">4</div>
                <div class="step-title">Pago</div>
                <div class="step-connector"></div>
            </div>
        </div>
        
        <div class="form-content">
            <!-- Paso 1: Tipo de Registro -->
            <div class="step-pane active" id="step1">
                <h3 class="text-center mb-4"><i class="fas fa-id-card-alt"></i> Seleccione su tipo de registro</h3>
                
                <div class="radio-container">
                    <div class="custom-radio" id="with-cedula">
                        <div class="radio-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h5>Doctores</h5>
                        <p class="text-muted small">Para médicos con cédula profesional</p>
                    </div>
                    
                    <div class="custom-radio" id="without-cedula">
                        <div class="radio-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5>Otros</h5>
                        <p class="text-muted small">Para otros profesionales de la salud</p>
                    </div>
                </div>
                
                <div class="text-center btn-navigation">
                    <button class="btn btn-primary" id="next-to-step2" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            
            <!-- Paso 2: Selección de Paquete -->
            <div class="step-pane" id="step2">
                <h3 class="text-center mb-4"><i class="fas fa-box-open"></i> Seleccione un Paquete</h3>
                
                <div class="row" id="packages-container">
                    <!-- Aquí se cargarán dinámicamente los paquetes -->
                </div>
                
                <div class="text-center btn-navigation">
                    <button class="btn btn-secondary mr-2" id="back-to-step1"><i class="fas fa-arrow-left"></i> Anterior</button>
                    <button class="btn btn-primary" id="next-to-step3" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            
            <!-- Paso 3: Información Personal -->
            <div class="step-pane" id="step3">
                <h3 class="text-center mb-4"><i class="fas fa-user-plus"></i> Complete su información</h3>
                
                <!-- Resumen de selecciones anteriores -->
                <div class="selected-options-summary">
                    <h5><i class="fas fa-check-circle"></i> Resumen de selecciones</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                <span class="summary-label">Tipo de registro:</span>
                                <span id="summary-registro-tipo"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-item">
                                <span class="summary-label">Paquete seleccionado:</span>
                                <span id="summary-paquete-nombre"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                <span class="summary-label">Precio:</span>
                                <span id="summary-paquete-precio"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
                @php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

                @if (config('adminlte.use_route_url', false))
                    @php( $login_url = $login_url ? route($login_url) : '' )
                    @php( $register_url = $register_url ? route($register_url) : '' )
                @else
                    @php( $login_url = $login_url ? url($login_url) : '' )
                    @php( $register_url = $register_url ? url($register_url) : '' )
                @endif
                <form action="{{ $register_url }}" method="post" id="registration-form">
                    @csrf
                    <!-- Campos ocultos para los valores de los pasos anteriores -->
                    <input type="hidden" id="tipo-registro" name="tipo-registro">
                    <input type="hidden" id="paquete-id" name="paquete-id">
                    <input type="hidden" id="paquete-nombre" name="paquete-nombre">
                    <input type="hidden" id="paquete-precio" name="paquete-precio">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre"><i class="fas fa-user"></i> Nombre</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nombre" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido-paterno"><i class="fas fa-user"></i> Apellido Paterno</label>
                                <input type="text" class="form-control @error('vapellido') is-invalid @enderror" name="vapellido" id="apellido-paterno" value="{{ old('vapellido') }}" required>
                            </div>
                            @error('vapellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido-materno"><i class="fas fa-user"></i> Apellido Materno</label>
                                <input type="text" class="form-control @error('segundo_apellido') is-invalid @enderror" name="segundo_apellido" id="apellido-materno" required>
                            </div>
                            @error('vapellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="tel" class="form-control @error('ttelefono') is-invalid @enderror" name="ttelefono" id="telefono" required>
                            </div>
                            @error('ttelefono')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="tdireccion" value="{{ old('tdireccion') }}" required>
                        @error('tdireccion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <!-- Campo de cédula y especialidad (se mostrará u ocultará según la selección) -->
                    <div class="row" id="cedula-field">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedula"><i class="fas fa-id-card"></i> Cédula Profesional</label>
                                <input type="text" class="form-control @error('vcedula') is-invalid @enderror" name="vcedula" id="cedula" value="{{ old('vcedula') }}" required>
                            </div>
                            @error('vcedula')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="especialidad"><i class="fas fa-stethoscope"></i> Especialidad</label>
                                <select class="form-control" name="especialidad" id="especialidad" required>
                                    <option value="">Seleccione especialidad</option>
                                    <option value="medicina-general">Medicina General</option>
                                    <option value="cardiologia">Cardiología</option>
                                    <option value="pediatria">Pediatría</option>
                                    <option value="ginecologia">Ginecología</option>
                                    <option value="neurologia">Neurología</option>
                                    <option value="dermatologia">Dermatología</option>
                                    <option value="oftalmologia">Oftalmología</option>
                                    <option value="otra">Otra</option>
                                </select>
                            </div>
                            @error('especialidad')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfc"><i class="fas fa-file-invoice"></i> RFC</label>
                                <input type="text" class="form-control  @error('RFC') is-invalid @enderror" name="RFC" id="rfc" value="{{ old('RFC') }}" required>
                            </div>
                            @error('RFC')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm-password"><i class="fas fa-lock"></i> Verificar Contraseña</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="confirm-password" required>
                            </div>
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="text-center btn-navigation">
                        <button type="button" class="btn btn-secondary mr-2" id="back-to-step2"><i class="fas fa-arrow-left"></i> Anterior</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Completar Registro</button>
                    </div>
                </form>
            </div>
            <!-- Paso 4: Pago -->
            <div class="step-pane" id="step4">
                <h3 class="text-center mb-4"><i class="fas fa-credit-card"></i> Información de Pago</h3>
                
                <!-- Resumen final de la compra -->
                <div class="payment-summary mb-4">
                    <h5>Resumen de la Compra</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Paquete:</strong> <span id="payment-package-name"></span></p>
                                    <p><strong>Tipo de Registro:</strong> <span id="payment-registration-type"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total a Pagar:</strong> <span id="payment-total"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Formulario de tarjeta de crédito -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="card-number">Número de Tarjeta</label>
                            <input type="text" class="form-control" id="card-number" placeholder="1234 5678 9012 3456" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card-expiry">Fecha de Expiración</label>
                                    <input type="text" class="form-control" id="card-expiry" placeholder="MM/AA" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card-cvv">CVV</label>
                                    <input type="text" class="form-control" id="card-cvv" placeholder="123" required>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group">
                            <label for="card-name">Nombre en la Tarjeta</label>
                            <input type="text" class="form-control" id="card-name" placeholder="Como aparece en la tarjeta" required>
                        </div>
                    </div>
                </div>
            
                <div class="text-center btn-navigation">
                    <button type="button" class="btn btn-secondary mr-2" id="back-to-step3">
                        <i class="fas fa-arrow-left"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-success" id="complete-payment">
                        <i class="fas fa-lock"></i> Pagar y Finalizar
                    </button>
                </div>
            </div>
            <!-- Modal de Confirmación -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem; margin: 20px;"></i>
                            <h4>¡Registro Exitoso!</h4>
                            <p>El pago se ha procesado correctamente. Por favor, revise su correo electrónico para activar su cuenta y comenzar a usar nuestros servicios.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
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
    
   <script src="{{ asset('js/principal.js') }}"></script>
</body>
</html>
{{-- @extends('adminlte::auth.auth-page', ['auth_type' => 'register']) --}}

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.register_message'))

@section('auth_body')



    <form action="{{ $register_url }}" method="post">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="NOMBRE(S)" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
       
        <div class="input-group mb-3">
            <input type="text" name="vapellido" class="form-control @error('vapellido') is-invalid @enderror"
                   value="{{ old('vapellido') }}" placeholder="APELLIDO PATERNO" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('vapellido')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="input-group mb-3">
            <input type="text" name="segundo_apellido" class="form-control @error('vapellido') is-invalid @enderror"
                   value="{{ old('vapellido') }}" placeholder="APELLIDO MATERNO" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('vapellido')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="input-group mb-3">
            <input type="text" name="ttelefono" class="form-control @error('ttelefono') is-invalid @enderror"
                   value="{{ old('ttelefono') }}" placeholder="TELEFONO" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('ttelefono')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="input-group mb-3">
            <input type="text" name="tdireccion" class="form-control @error('tdireccion') is-invalid @enderror"
                   value="{{ old('tdireccion') }}" placeholder="DIRECCIÓN" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('tdireccion')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        
        
        <div class="input-group mb-3">
            <input type="text" name="vcedula" class="form-control @error('vcedula') is-invalid @enderror"
                   value="{{ old('vcedula') }}" placeholder="CÉDULA PROFESIONAL" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('vcedula')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
       
        <div class="input-group mb-3">
            <input type="text" name="RFC" class="form-control @error('RFC') is-invalid @enderror"
                   value="{{ old('RFC') }}" placeholder="RFC" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('RFC')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
       
        <div class="input-group mb-3">
            <input type="text" name="especialidad" class="form-control @error('especialidad') is-invalid @enderror"
                   value="{{ old('especialidad') }}" placeholder="ESPECIALIDAD O TÍTULO" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('especialidad')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.retype_password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('adminlte::adminlte.register') }}
        </button>

    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $login_url }}">
            {{ __('adminlte::adminlte.i_already_have_a_membership') }}
        </a>
    </p>
@stop

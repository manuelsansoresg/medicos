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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .registration-container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0061a8 0%, #003b67 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .steps {
            display: flex;
            justify-content: space-between;
            padding: 20px 40px;
            background-color: #f1f7ff;
            border-bottom: 1px solid #e3e3e3;
        }
        .step {
            text-align: center;
            position: relative;
            flex: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e3e3e3;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }
        .step.active .step-number {
            background-color: #0061a8;
            color: white;
        }
        .step.completed .step-number {
            background-color: #28a745;
            color: white;
        }
        .step-title {
            font-size: 14px;
            color: #666;
        }
        .step.active .step-title {
            color: #0061a8;
            font-weight: bold;
        }
        .step.completed .step-title {
            color: #28a745;
        }
        .step-connector {
            position: absolute;
            top: 20px;
            height: 2px;
            background-color: #e3e3e3;
            width: 100%;
            left: -50%;
            z-index: 1;
        }
        .step:first-child .step-connector {
            display: none;
        }
        .step.completed .step-connector {
            background-color: #28a745;
        }
        .form-content {
            padding: 30px;
        }
        .step-pane {
            display: none;
        }
        .step-pane.active {
            display: block;
        }
        .package-card {
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .package-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .package-card.selected {
            border: 2px solid #0061a8;
            box-shadow: 0 5px 15px rgba(0, 97, 168, 0.2);
        }
        .package-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 8px 8px 0 0;
            border-bottom: 1px solid #e3e3e3;
        }
        .package-body {
            padding: 20px;
        }
        .package-feature {
            margin-bottom: 10px;
            color: #495057;
        }
        .package-feature i {
            color: #28a745;
            margin-right: 8px;
        }
        .btn-navigation {
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #0061a8;
            border-color: #0061a8;
        }
        .btn-primary:hover {
            background-color: #003b67;
            border-color: #003b67;
        }
        .credentials-toggle {
            margin-bottom: 20px;
        }
        .radio-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
        }
        .custom-radio {
            cursor: pointer;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e3e3e3;
            text-align: center;
            transition: all 0.3s ease;
            width: 200px;
        }
        .custom-radio:hover {
            background-color: #f1f7ff;
        }
        .custom-radio.selected {
            background-color: #e6f2ff;
            border-color: #0061a8;
        }
        .radio-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #0061a8;
        }
        .selected-options-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e3e3e3;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            color: #495057;
        }
    </style>
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
                        <h5>Con Cédula Profesional</h5>
                        <p class="text-muted small">Para médicos certificados</p>
                    </div>
                    
                    <div class="custom-radio" id="without-cedula">
                        <div class="radio-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5>Sin Cédula Profesional</h5>
                        <p class="text-muted small">Para estudiantes y otros profesionales</p>
                    </div>
                </div>
                
                <div class="text-center btn-navigation">
                    <button class="btn btn-primary" id="next-to-step2" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            
            <!-- Paso 2: Selección de Paquete -->
            <div class="step-pane" id="step2">
                <h3 class="text-center mb-4"><i class="fas fa-box-open"></i> Seleccione un Paquete</h3>
                
                <div class="row">
                    <!-- Paquete Básico -->
                    <div class="col-md-4">
                        <div class="package-card" id="package-basic" data-package-name="Paquete Básico" data-package-price="99">
                            <div class="package-header">
                                <h4 class="text-center"> Paquete Básico</h4>
                            </div>
                            <div class="package-body">
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 1 Clínica
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 2 Usuarios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 2 Consultorios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 50 Pacientes
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> Soporte Básico
                                </div>
                                <div class="text-center mt-3">
                                    <h5>$99/mes</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paquete Estándar -->
                    <div class="col-md-4">
                        <div class="package-card" id="package-standard" data-package-name="Paquete Estándar" data-package-price="199">
                            <div class="package-header">
                                <h4 class="text-center"> Paquete Estándar</h4>
                            </div>
                            <div class="package-body">
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 2 Clínicas
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 5 Usuarios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 4 Consultorios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 150 Pacientes
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> Soporte Prioritario
                                </div>
                                <div class="text-center mt-3">
                                    <h5>$199/mes</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paquete Premium -->
                    <div class="col-md-4">
                        <div class="package-card" id="package-premium" data-package-name="Paquete Premium" data-package-price="299">
                            <div class="package-header">
                                <h4 class="text-center">  Paquete Premium</h4>
                            </div>
                            <div class="package-body">
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 5 Clínicas
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 15 Usuarios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> 10 Consultorios
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> Pacientes Ilimitados
                                </div>
                                <div class="package-feature">
                                    <i class="fas fa-check"></i> Soporte 24/7
                                </div>
                                <div class="text-center mt-3">
                                    <h5>$299/mes</h5>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <form action="{{ $register_url }}" method="post">
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
                                <input type="text" class="form-control" id="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido-paterno"><i class="fas fa-user"></i> Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido-paterno" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="apellido-materno"><i class="fas fa-user"></i> Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido-materno" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección</label>
                        <input type="text" class="form-control" id="direccion" required>
                    </div>
                    
                    <!-- Campo de cédula y especialidad (se mostrará u ocultará según la selección) -->
                    <div class="row" id="cedula-field">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedula"><i class="fas fa-id-card"></i> Cédula Profesional</label>
                                <input type="text" class="form-control" id="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="especialidad"><i class="fas fa-stethoscope"></i> Especialidad</label>
                                <select class="form-control" id="especialidad" required>
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
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfc"><i class="fas fa-file-invoice"></i> RFC</label>
                                <input type="text" class="form-control" id="rfc" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm-password"><i class="fas fa-lock"></i> Verificar Contraseña</label>
                                <input type="password" class="form-control" id="confirm-password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center btn-navigation">
                        <button type="button" class="btn btn-secondary mr-2" id="back-to-step2"><i class="fas fa-arrow-left"></i> Anterior</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Completar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap & jQuery JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Variables
            let hasCedula = null;
            let selectedPackage = null;
            let packageName = "";
            let packagePrice = 0;
            
            // Paso 1: Selección de tipo de registro
            $('.custom-radio').click(function() {
                $('.custom-radio').removeClass('selected');
                $(this).addClass('selected');
                
                if ($(this).attr('id') === 'with-cedula') {
                    hasCedula = true;
                } else {
                    hasCedula = false;
                }
                
                $('#next-to-step2').prop('disabled', false);
            });
            
            // Paso 2: Selección de paquete
            $('.package-card').click(function() {
                $('.package-card').removeClass('selected');
                $(this).addClass('selected');
                selectedPackage = $(this).attr('id');
                packageName = $(this).data('package-name');
                packagePrice = $(this).data('package-price');
                $('#next-to-step3').prop('disabled', false);
            });
            
            // Navegación hacia adelante
            $('#next-to-step2').click(function() {
                $('#step1').removeClass('active');
                $('#step2').addClass('active');
                $('#step1-indicator').removeClass('active').addClass('completed');
                $('#step2-indicator').addClass('active');
            });
            
            $('#next-to-step3').click(function() {
                $('#step2').removeClass('active');
                $('#step3').addClass('active');
                $('#step2-indicator').removeClass('active').addClass('completed');
                $('#step3-indicator').addClass('active');
                
                // Actualizar el resumen y los campos ocultos con los valores seleccionados
                const tipoRegistroTexto = hasCedula ? 'Con Cédula Profesional' : 'Sin Cédula Profesional';
                
                // Actualizar el resumen visual
                $('#summary-registro-tipo').text(tipoRegistroTexto);
                $('#summary-paquete-nombre').text(packageName);
                $('#summary-paquete-precio').text('$' + packagePrice + '/mes');
                
                // Actualizar los campos ocultos del formulario
                $('#tipo-registro').val(hasCedula ? 'con_cedula' : 'sin_cedula');
                $('#paquete-id').val(selectedPackage);
                $('#paquete-nombre').val(packageName);
                $('#paquete-precio').val(packagePrice);
                
                // Mostrar u ocultar campos de cédula según la selección
                if (hasCedula) {
                    // Si eligió "Con Cédula", mostrar el campo y hacerlo requerido
                    $('#cedula-field').show();
                    $('#cedula').prop('required', true);
                    $('#especialidad').prop('required', true);
                } else {
                    // Si eligió "Sin Cédula", ocultar el campo y quitar el required
                    $('#cedula-field').hide();
                    $('#cedula').prop('required', false);
                    $('#especialidad').prop('required', false);
                }
            });
            
            // Navegación hacia atrás
            $('#back-to-step1').click(function() {
                $('#step2').removeClass('active');
                $('#step1').addClass('active');
                $('#step2-indicator').removeClass('active');
                $('#step1-indicator').removeClass('completed').addClass('active');
            });
            
            $('#back-to-step2').click(function() {
                $('#step3').removeClass('active');
                $('#step2').addClass('active');
                $('#step3-indicator').removeClass('active');
                $('#step2-indicator').removeClass('completed').addClass('active');
            });
            
            // Envío del formulario
            $('#registration-form').submit(function(e) {
                e.preventDefault();
                
                // Validar que las contraseñas coincidan
                if ($('#password').val() !== $('#confirm-password').val()) {
                    alert('Las contraseñas no coinciden');
                    return;
                }
                
                // Aquí normalmente enviarías los datos al servidor
                alert('¡Registro completado con éxito!');
                
                // Datos para enviar
                const formData = {
                    tipoRegistro: $('#tipo-registro').val(),
                    paqueteId: $('#paquete-id').val(),
                    paqueteNombre: $('#paquete-nombre').val(),
                    paquetePrecio: $('#paquete-precio').val(),
                    nombre: $('#nombre').val(),
                    apellidoPaterno: $('#apellido-paterno').val(),
                    apellidoMaterno: $('#apellido-materno').val(),
                    telefono: $('#telefono').val(),
                    email: $('#email').val(),
                    direccion: $('#direccion').val(),
                    rfc: $('#rfc').val()
                };
                
                // Añadir cédula y especialidad solo si seleccionó "Con Cédula"
                if (hasCedula) {
                    formData.cedula = $('#cedula').val();
                    formData.especialidad = $('#especialidad').val();
                }
                
                console.log('Datos del formulario:', formData);
            });
        });
    </script>
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

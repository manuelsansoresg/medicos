<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Médico - Comprobante de Pago</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-logo {
            max-height: 80px;
        }
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container p-4 p-md-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        
                        <h2 class="text-primary"><i class="fas fa-user-md"></i> Sistema Médico</h2>
                        <p class="text-muted">Portal de Verificación de Pagos</p>
                    </div>
                    
                    <!-- Alert Info -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i> Para activar su acceso al Sistema Médico, por favor adjunte su comprobante de pago y complete el formulario a continuación.
                    </div>
                    
                    <!-- Form -->
                    <form id="comprobanteForm">
                        @csrf
                        <div class="row g-3">
                            <!-- Datos Personales -->
                            <div class="col-12">
                                <h5><i class="fas fa-user me-2"></i>Datos personales</h5>
                                <hr>
                            </div>
                            
                          
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Correo Electrónico*</label>
                                <div class="input-group">
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            
                            
                            
                            <!-- Datos de Pago -->
                            <div class="col-12 mt-3">
                                <h5><i class="fas fa-credit-card me-2"></i>Información del Pago</h5>
                                <hr>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipoPago" class="form-label">Tipo de Pago*</label>
                                <select class="form-select" id="tipoPago" name="data[payment_type]" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="transferencia">Transferencia bancaria</option>
                                    <option value="deposito">Depósito bancario</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fechaPago" class="form-label">Fecha de Pago*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" id="fechaPago" name="data[fecha_pago]" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="monto" class="form-label">Monto Pagado ($)*</label>
                                <div class="input-group">
                                    <span>${{ format_price($package->precio) }}</span>
                                </div>
                            </div>
                            
                            <!-- Comprobante -->
                            <div class="col-12 mt-3">
                                <h5><i class="fas fa-file-invoice me-2"></i>Comprobante de Pago</h5>
                                <hr>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="comprobante" class="form-label">Adjuntar Comprobante*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                    <input type="file" class="form-control" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required>
                                </div>
                                <div class="form-text">Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 5MB</div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="data[observaciones]" rows="3" placeholder="Ingrese cualquier información adicional que considere relevante"></textarea>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terminos" required>
                                    <label class="form-check-label" for="terminos">
                                        Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a> del servicio*
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" id='solicitudId' value="{{ $solicitud->id }}">
                            <!-- Botones -->
                            <div class="col-12 mt-3 d-grid gap-2 d-md-flex justify-content-md-end">
                               
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Comprobante
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-4 text-muted">
                    <small>&copy; 2025 Sistema Médico. Todos los derechos reservados.</small>
                    <div class="mt-2">
                        <i class="fas fa-phone-alt me-2"></i>Soporte: (123) 456-7890
                        <span class="mx-2">|</span>
                        <i class="fas fa-envelope me-2"></i>ayuda@sistemamedico.com
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Términos y Condiciones -->
    <div class="modal fade" id="terminosModal" tabindex="-1" aria-labelledby="terminosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terminosModalLabel">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Política de Privacidad y Términos de Uso</h6>
                    <p>Al utilizar este servicio, usted acepta los siguientes términos y condiciones:</p>
                    <ol>
                        <li>La información proporcionada será utilizada exclusivamente para verificar su pago y activar su acceso al Sistema Médico.</li>
                        <li>Sus datos personales serán tratados con confidencialidad según nuestra política de privacidad.</li>
                        <li>El comprobante de pago será verificado en un plazo máximo de 24 horas hábiles.</li>
                        <li>Una vez verificado el pago, recibirá acceso al correo electrónico registrado.</li>
                        <li>El acceso al sistema tiene una validez de acuerdo al plan contratado.</li>
                        <li>No se realizarán reembolsos una vez activado el servicio.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/principal.js') }}"></script>
    
    
</body>
</html>
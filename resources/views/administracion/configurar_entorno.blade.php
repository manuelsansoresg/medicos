@extends('layouts.template')

@inject('Msolicitud', 'App\Models\Solicitud')
@inject('Mclinica', 'App\Models\Clinica')
@inject('Mconsultorio', 'App\Models\Consultorio')



@section('content')
<div class="wizard-container mt-5">
    <div class="header">
        <h1><i class="fas fa-cogs"></i> Configura tu Panel</h1>
        <p>Configura tu panel para poder usar el sistema</p>
    </div>
    
    <div class="steps">
        <div class="step active" id="step1-indicator">
            <div class="step-number">1</div>
            <div class="step-title">Selección de Entorno</div>
            <div class="step-connector"></div>
        </div>
        <div class="step" id="step2-indicator">
            <div class="step-number">2</div>
            <div class="step-title">Clínica</div>
            <div class="step-connector"></div>
        </div>
        <div class="step" id="step3-indicator">
            <div class="step-number">3</div>
            <div class="step-title">Consultorio</div>
            <div class="step-connector"></div>
        </div>
        <div class="step" id="step4-indicator">
            <div class="step-number">4</div>
            <div class="step-title">Horarios</div>
            <div class="step-connector"></div>
        </div>
    </div>
    
    <div class="form-content">
        <!-- Paso 1: Selección de Entorno -->
        <div class="step-pane active" id="step1">
            <h3 class="text-center mb-4"><i class="fas fa-building"></i> Seleccione su entorno</h3>
            
            <div class="radio-container">
                <div class="custom-radio" id="clinica-only">
                    <div class="radio-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h5>Clínica</h5>
                    <p class="text-muted small">Solo clínica</p>
                </div>
                
                <div class="custom-radio" id="consultorio-only">
                    <div class="radio-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h5>Consultorio</h5>
                    <p class="text-muted small">Solo consultorio</p>
                </div>
                
                <div class="custom-radio" id="ambos">
                    <div class="radio-icon">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <h5>Ambos</h5>
                    <p class="text-muted small">Clínica y consultorio</p>
                </div>
            </div>
            
            <div class="text-center btn-navigation">
                <button class="btn btn-primary" id="next-to-step2" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        
        <!-- Paso 2: Crear Clínica -->
        <div class="step-pane" id="step2">
            <h3 class="text-center mb-4"><i class="fas fa-hospital"></i> Configurar Clínica</h3>
            
            <form id="frm-clinica-wizard">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombreClinica" class="form-label">*NOMBRE</label>
                            <input type="text" class="form-control" name="data[tnombre]" id="inputNombreClinica" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputDireccionClinica" class="form-label">DIRECCIÓN</label>
                            <input type="text" class="form-control" name="data[tdireccion]" id="inputDireccionClinica">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputRfcClinica" class="form-label">RFC</label>
                            <input type="text" class="form-control" name="data[vrfc]" id="inputRfcClinica">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefonoClinica" class="form-label">TELÉFONO</label>
                            <input type="text" class="form-control" name="data[ttelefono]" id="inputTelefonoClinica">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputFolioClinica" class="form-label">*FOLIO</label>
                            <input type="text" class="form-control" name="data[vfolioclinica]" id="inputFolioClinica" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputEstatusClinica" class="form-label">*ACTIVO</label>
                            <select name="data[istatus]" id="inputEstatusClinica" class="form-control" required>
                                @foreach (config('enums.status') as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="text-center btn-navigation">
                <button class="btn btn-secondary mr-2" id="back-to-step1"><i class="fas fa-arrow-left"></i> Anterior</button>
                <button class="btn btn-primary" id="next-to-step3" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        
        <!-- Paso 3: Crear Consultorio -->
        <div class="step-pane" id="step3">
            <h3 class="text-center mb-4"><i class="fas fa-stethoscope"></i> Configurar Consultorio</h3>
            
            <form id="frm-consultorio-wizard">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombreConsultorio" class="form-label">*NOMBRE</label>
                            <input type="text" class="form-control" name="data[vnumconsultorio]" id="inputNombreConsultorio" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputUbicacionConsultorio" class="form-label">UBICACIÓN</label>
                            <textarea name="data[thubicacion]" id="inputUbicacionConsultorio" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefonoConsultorio" class="form-label">TELÉFONO</label>
                            <input type="text" class="form-control" name="data[ttelefono]" id="inputTelefonoConsultorio">
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="text-center btn-navigation">
                <button class="btn btn-secondary mr-2" id="back-to-step2"><i class="fas fa-arrow-left"></i> Anterior</button>
                <button class="btn btn-primary" id="next-to-step4" disabled>Continuar <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        
        <!-- Paso 4: Configurar Horarios -->
        <div class="step-pane" id="step4">
            <h3 class="text-center mb-4"><i class="fas fa-clock"></i> Configurar Horarios de Atención</h3>
            
            <form id="frm-horarios-wizard">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    <div class="col-md-6" id="content-financial_product_id">
                        <div class="form-group">
                            <label class="form-label">CLINICA</label>
                            @php
                                $clinicas = $Mclinica::getAll();
                            @endphp
                            <div class="form-control-wrap">
                                <select name="data[idclinica]" id="clinica-wizard" class="form-control" onchange="changeOfficeWizard({{ Auth::user()->id }})">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($clinicas as $clinica)
                                        <option value="{{ $clinica->idclinica }}">{{ $clinica->tnombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="content-financial_product_id">
                        <div class="form-group">
                            <label class="form-label">CONSULTORIO</label>
                            @php
                                $consultorios = $Mconsultorio::getAll();
                            @endphp
                            <div class="form-control-wrap">
                                <select name="data[idconsultorio]" id="offices-wizard"  class="form-control" onchange="changeOfficeWizard({{ Auth::user()->id }})">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($consultorios as $consultorio)
                                        <option value="{{ $consultorio->idconsultorios }}">{{ $consultorio->vnumconsultorio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                 
                    <div class="col-12">
                        <h6>SELECCIONE LAS HORAS DE CONSULTA</h6>
                    </div>

                    <div class="col-12" id="content-horario-consulta">

                    </div>
                    
                    <div id="content-duracion-consulta-wizard" style="display: none">
                        <div class="col-12">
                            <h6>SELECCIONE CUANTO TIEMPO DURA LA CONSULTA</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">DURACIÓN CONSULTA</label>
                                <div class="form-control-wrap">
                                    <select name="duraconsulta" id="duraconsulta-wizard" class="form-control">
                                        <option value="15">15 MINUTOS</option>
                                        <option value="20">20 MINUTOS</option>
                                        <option value="30">30 MINUTOS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="text-center btn-navigation">
                <button class="btn btn-secondary mr-2" id="back-to-step3"><i class="fas fa-arrow-left"></i> Anterior</button>
                <button class="btn btn-success" id="finalizar-configuracion" disabled><i class="fas fa-check-circle"></i> Finalizar Configuración</button>
            </div>
        </div>
    </div>
</div>

<!-- Campos ocultos para almacenar datos -->
<input type="hidden" id="entorno-seleccionado" name="entorno-seleccionado">
<input type="hidden" id="clinica-id" name="clinica-id">
<input type="hidden" id="consultorio-id" name="consultorio-id">
<input type="hidden" id="horarios-configurados" name="horarios-configurados">
<script>
    window.userId = {{ auth()->user()->id }};
</script>
@endsection




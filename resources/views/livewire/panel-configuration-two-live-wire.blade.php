<div>
    {{-- Stop trying to control. --}}
</div>
<div class="wizard-container mt-5">
    <div class="header">
    <div id="scroll-anchor-consultorio"></div>
        <h1><i class="fas fa-cogs"></i> Configura tu Panel</h1>
        <p>Configura tu panel para poder usar el sistema</p>
    </div>


    <div class="steps">
        {{-- si eligio clinica --}}
        @if ($typeConfiguration == 1)
            <div class="step  {{ $tab == 1 ? 'active' : '' }}" id="step1-clinic">
                <div class="step-number">1</div>
                <div class="step-title">Clínica</div>
                <div class="step-connector"></div>
            </div>
            @php
                $indiceConsultorio = 1;
            @endphp
            @for ($iConsultorio = 1; $iConsultorio <= $totalConsultorio; $iConsultorio++)
                @php
                    $headTagNumConsultory = $indiceConsultorio + 1;
                    $headContConsultory = $iConsultorio + 1;
                @endphp
                <div class="step {{ $tab == $headContConsultory ? 'active' : '' }}" id="step-cons-{{ $headTagNumConsultory }}">
                    
                    <div class="step-number">{{ $headContConsultory }}</div>
                    <div class="step-title">Consultorio {{ $iConsultorio }}</div>
                    <div class="step-connector"></div>
                </div>
            @endfor
        @else
            {{-- si eligio solo consultorios --}}
            @for ($iConsultorio = 1; $iConsultorio <= $totalConsultorio; $iConsultorio++)
                <div class="step {{ $tab == $iConsultorio ? 'active' : '' }}" id="step-cons-{{ $iConsultorio }}">
                    <div class="step-number">{{ $iConsultorio }}</div>
                    <div class="step-title">Consultorio {{ $iConsultorio }}</div>
                    <div class="step-connector"></div>
                </div>
            @endfor
        @endif
    </div>

    <div class="form-content">
        {{-- si eligio clinica --}}
        @if ($typeConfiguration == 1)
            <div class="step-pane {{ $tab == 1 ? 'active' : '' }}" id="step1-clinic">
                <h3 class="text-center mb-4"><i class="fas fa-hospital"></i> Configurar Clínica</h3>

                {{-- Mensajes de éxito y error --}}
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="frm-clinica-wizard" wire:key="clinica-form-{{ $idClinica }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombreClinica" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" wire:model.live="clinicaData.tnombre" id="inputNombreClinica"
                                    value="{{ $clinicaData['tnombre'] }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputDireccionClinica" class="form-label">DIRECCIÓN</label>
                                <input type="text" class="form-control" wire:model.defer="clinicaData.tdireccion"
                                    id="inputDireccionClinica" value="{{ $clinicaData['tdireccion'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputRfcClinica" class="form-label">RFC</label>
                                <input type="text" class="form-control" wire:model.defer="clinicaData.vrfc" id="inputRfcClinica"
                                    value="{{ $clinicaData['vrfc'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputTelefonoClinica" class="form-label">TELÉFONO</label>
                                <input type="text" class="form-control" wire:model.defer="clinicaData.ttelefono"
                                    id="inputTelefonoClinica" value="{{ $clinicaData['ttelefono'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputFolioClinica" class="form-label">*FOLIO</label>
                                <input type="text" class="form-control" wire:model.live="clinicaData.vfolioclinica"
                                    id="inputFolioClinica" value="{{ $clinicaData['vfolioclinica'] }}" required>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputEstatusClinica" class="form-label">*ACTIVO</label>
                                <select name="data[istatus]" id="inputEstatusClinica" class="form-control" required>
                                    @foreach (config('enums.status') as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                    </div>
                </form>

                <div class="text-center btn-navigation">
                   
                    <button class="btn btn-primary" wire:click="guardarClinica">
                        Continuar <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                
               
            </div>
        @endif
        {{-- consultorios si configuracion es 1 --}}
        @if ($typeConfiguration == 1)
            <!-- Paso 2: Crear Consultorio (para type_configuration = 1) -->
            @for ($iConsultorio = 1; $iConsultorio <= $totalConsultorio; $iConsultorio++)
                @php
                    $TagNumConsultory = $indiceConsultorio + 1;
                    $ContConsultory = $iConsultorio + 1;
                @endphp
                <div class="step-pane {{ $tab == $ContConsultory ? 'active' : '' }}" id="step-cons-{{ $ContConsultory }}">
                
                <!-- Mensajes de feedback -->
                @if (session()->has('message'))
                    
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                         {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <h3 class="text-center mb-4"><i class="fas fa-stethoscope"></i> Configurar consultorio
                    {{ $iConsultorio }} de {{ $totalConsultorio }} </h3>
                    <a class="section-title"></a>
                <div id="scroll-target-consultorio" style="padding-top: 20px; margin-top: -20px;"></div>
                <form id="frm-consultorio-wizard">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombreConsultorio{{ $iConsultorio }}" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" 
                                    wire:model="consultoriosData.{{ $iConsultorio - 1 }}.vnumconsultorio"
                                    id="inputNombreConsultorio{{ $iConsultorio }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputUbicacionConsultorio{{ $iConsultorio }}" class="form-label">UBICACIÓN</label>
                                <textarea wire:model="consultoriosData.{{ $iConsultorio - 1 }}.thubicacion" 
                                    id="inputUbicacionConsultorio{{ $iConsultorio }}" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputTelefonoConsultorio{{ $iConsultorio }}" class="form-label">TELÉFONO</label>
                                <input type="text" class="form-control" 
                                    wire:model="consultoriosData.{{ $iConsultorio - 1 }}.ttelefono"
                                    id="inputTelefonoConsultorio{{ $iConsultorio }}">
                            </div>
                        </div>
                        <h3 class="text-center mb-4 mt-3"><i class="fas fa-clock"></i> Configurar horarios por día</h3>
                        

                        
                        @if(!$dias_configurados)
                            <!-- Pregunta inicial para seleccionar días laborales -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> ¿Qué días labora?</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">Seleccione los días en los que atiende pacientes:</p>
                                            <div class="row">
                                    @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input dia-checkbox" type="checkbox" 
                                                       data-dia="{{ $dia }}"
                                                       id="dia_{{ $dia }}">
                                                <label class="form-check-label" for="dia_{{ $dia }}">
                                                    {{ ucfirst($dia) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                            <div class="mt-3">
                                                <button type="button" onclick="configurarHorarios()" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Guardar y Configurar Horarios
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Días configurados como botones -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="text-success mb-0">
                                            <i class="fas fa-check-circle"></i> Días laborales configurados: 
                                            <strong>{{ implode(', ', array_map('ucfirst', $dias_laborales)) }}</strong>
                                        </p>
                                        <button type="button" 
                                                wire:click="volverSeleccionDias" 
                                                class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-edit"></i> Cambiar días
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-center flex-wrap" style="gap: 0.5rem;">
                                        @foreach($dias_laborales as $dia)
                                            <button type="button" 
                                                    wire:click="cambiarDia('{{ $dia }}')"
                                                    class="btn {{ $dia_seleccionado === $dia ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                                {{ ucfirst($dia) }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($dias_configurados)
                            <!-- Sección de Horarios -->
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-info mb-4">Configura los rangos de horarios para <strong>{{ ucfirst($dia_seleccionado) }}</strong>. Puedes
                                        ajustar las horas de inicio y fin para cada período del día.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mt-3">
                                    <!-- Turno Mañana -->
                                    <div class="horario-section horario-manana">
                                        <div class="horario-header">
                                            <h4 class="horario-title"> <i class="fas fa-sun"></i> Turno de Mañana</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Inicio</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_inicio" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_manana as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Fin</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_fin" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_manana as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-3">
                                    <!-- Turno Tarde -->
                                    <div class="horario-section horario-tarde">
                                        <div class="horario-header">
                                            <h4 class="horario-title"> <i class="fas fa-sun"></i> Turno de Tarde</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Inicio</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_inicio" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_tarde as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Fin</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_fin" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_tarde as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-3">
                                    <!-- Turno Noche -->
                                    <div class="horario-section horario-noche">
                                        <div class="horario-header">
                                            <h4 class="horario-title"><i class="fas fa-moon"></i> Turno de Noche</h4>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Inicio</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_inicio" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_noche as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Fin</label>
                                                <select wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_fin" 
                                                        class="form-select form-select-sm">
                                                    @foreach($horarios_noche as $hora => $texto)
                                                        <option value="{{ $hora }}">{{ $texto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Sección de configuración de horarios (inicialmente oculta) -->
                        <div id="seccion-horarios" style="display: none;" class="mt-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-clock"></i> Configurar Horarios por Día</h5>
                                </div>
                                <div class="card-body">
                                    <div id="dias-seleccionados"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Sección de Duración de Consulta -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputDuracionConsulta{{ $iConsultorio }}" class="form-label">DURACIÓN CONSULTA</label>
                                    <select wire:model="consultoriosData.{{ $iConsultorio - 1 }}.duracion_consulta" 
                                            id="inputDuracionConsulta{{ $iConsultorio }}" 
                                            class="form-control">
                                        @foreach (config('enums.interval_hours') as $key => $itemHour)
                                            <option value="{{ $key }}">{{ $itemHour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if ($ContConsultory > 2)
                    <div class="col-12">
                        <p>
                           <i class="fa-solid fa-circle-info"></i> Puedes terminar la configuración más tarde. La información se guardará automáticamente 
                        </p>
                    </div>
                @endif

                <div class="text-center btn-navigation">
                    <button class="btn btn-secondary mr-2" wire:click="anteriorConsultorio({{ $ContConsultory }})"><i class="fas fa-arrow-left"></i>
                        Anterior</button>
                    @if ($ContConsultory > 2)
                        <button class="btn btn-primary" wire:click="finalizarConfiguracion">Finalizar <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                    @endif
                    <button class="btn btn-primary"
                        wire:click="guardarConsultorioYHorarios({{ $iConsultorio - 1 }}, {{  $ContConsultory }})"
                        wire:loading.attr="disabled"
                        wire:target="guardarConsultorioYHorarios"
                        onclick="window.scrollToConsultorio()"
                        @if($ContConsultory == 2 && !$this->hayConsultorioValido) disabled @endif>
                        Continuar <i class="fas fa-arrow-right"></i>
                    </button>
                   
                </div>
            </div>
            @endfor
        @else
            {{-- consultorios si configuracion es 2 (solo consultorios) --}}
            <!-- Crear Consultorio (para type_configuration = 2) -->
            @for ($iConsultorio = 1; $iConsultorio <= $totalConsultorio; $iConsultorio++)
                <div class="step-pane {{ $tab == $iConsultorio ? 'active' : '' }}" id="step-cons-{{ $iConsultorio }}">
                    
                    <!-- Mensajes de feedback -->
                    @if (session()->has('message'))
                        
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                             {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <h3 class="text-center mb-4"><i class="fas fa-stethoscope"></i> Configurar consultorio
                        {{ $iConsultorio }} de {{ $totalConsultorio }} </h3>
                        <a class="section-title"></a>
                    <div id="scroll-target-consultorio" style="padding-top: 20px; margin-top: -20px;"></div>
                    <form id="frm-consultorio-wizard">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <p class="text-info">Los campos marcados con * son requeridos</p>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputNombreConsultorio{{ $iConsultorio }}" class="form-label">*NOMBRE</label>
                                    <input type="text" class="form-control" 
                                        wire:model="consultoriosData.{{ $iConsultorio - 1 }}.vnumconsultorio"
                                        id="inputNombreConsultorio{{ $iConsultorio }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputUbicacionConsultorio{{ $iConsultorio }}" class="form-label">UBICACIÓN</label>
                                    <textarea wire:model="consultoriosData.{{ $iConsultorio - 1 }}.thubicacion" 
                                        id="inputUbicacionConsultorio{{ $iConsultorio }}" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputTelefonoConsultorio{{ $iConsultorio }}" class="form-label">TELÉFONO</label>
                                    <input type="text" class="form-control" 
                                        wire:model="consultoriosData.{{ $iConsultorio - 1 }}.ttelefono"
                                        id="inputTelefonoConsultorio{{ $iConsultorio }}">
                                </div>
                            </div>



                        </div>
                    </form>
                    @if ($iConsultorio > 1)
                        <div class="col-12">
                            <p>
                               <i class="fa-solid fa-circle-info"></i> Puedes terminar la configuración más tarde. La información se guardará automáticamente 
                            </p>
                        </div>
                    @endif

                    <div class="text-center btn-navigation">
                        @if ($iConsultorio > 1)
                            <button class="btn btn-secondary mr-2" wire:click="anteriorConsultorio({{ $iConsultorio }})"><i class="fas fa-arrow-left"></i>
                                Anterior</button>
                        @endif
                        @if ($iConsultorio > 1)
                            <button class="btn btn-primary" wire:click="finalizarConfiguracion">Finalizar <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                        @endif
                        <button class="btn btn-primary"
                            wire:click="guardarConsultorioYHorarios({{ $iConsultorio - 1 }}, {{ $iConsultorio }})"
                            wire:loading.attr="disabled"
                            wire:target="guardarConsultorioYHorarios"
                            onclick="window.scrollToConsultorio()"
                            @if($iConsultorio == 1 && (!$this->hayConsultorioValido || !$dias_configurados)) disabled @endif>
                            Continuar <i class="fas fa-arrow-right"></i>
                        </button>
                       
                    </div>
                </div>
            @endfor
        @endif
    </div>

</div>

<!-- Incluir JavaScript de validaciones -->


<script>
// Script inline para asegurar que funcione
function configurarHorarios() {
    console.log('Función configurarHorarios ejecutada');
    const checkboxes = document.querySelectorAll('.dia-checkbox:checked');
    console.log('Checkboxes encontrados:', checkboxes.length);
    
    if (checkboxes.length === 0) {
        alert('Debe seleccionar al menos un día laboral.');
        return;
    }
    
    // Crear la configuración de horarios
    let html = '';
    checkboxes.forEach(function(checkbox) {
        const dia = checkbox.dataset.dia;
        const diaCapitalizado = dia.charAt(0).toUpperCase() + dia.slice(1);
        
        html += `
            <div class="mb-4 border p-3 rounded">
                <h6 class="text-primary mb-3"><i class="fas fa-calendar-day"></i> ${diaCapitalizado}</h6>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Turno Mañana</label>
                        <div class="row">
                                     <div class="col-6">
                                         <label class="form-label small">Inicio</label>
                                         <select class="form-select form-select-sm">
                                             ${generarOpcionesHora(6, 'manana')}
                                         </select>
                                     </div>
                                     <div class="col-6">
                                         <label class="form-label small">Fin</label>
                                         <select class="form-select form-select-sm">
                                             ${generarOpcionesHora(14, 'manana')}
                                         </select>
                                     </div>
                                 </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Turno Tarde</label>
                         <div class="row">
                             <div class="col-6">
                                 <label class="form-label small">Inicio</label>
                                 <select class="form-select form-select-sm">
                                     ${generarOpcionesHora(12, 'tarde')}
                                 </select>
                             </div>
                             <div class="col-6">
                                 <label class="form-label small">Fin</label>
                                 <select class="form-select form-select-sm">
                                     ${generarOpcionesHora(22, 'tarde')}
                                 </select>
                             </div>
                         </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Turno Noche</label>
                         <div class="row">
                             <div class="col-6">
                                 <label class="form-label small">Inicio</label>
                                 <select class="form-select form-select-sm">
                                     ${generarOpcionesHora(18, 'noche')}
                                 </select>
                             </div>
                             <div class="col-6">
                                 <label class="form-label small">Fin</label>
                                 <select class="form-select form-select-sm">
                                     ${generarOpcionesHora(2, 'noche')}
                                 </select>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    const diasSeleccionados = document.getElementById('dias-seleccionados');
    const seccionHorarios = document.getElementById('seccion-horarios');
    
    if (diasSeleccionados && seccionHorarios) {
        diasSeleccionados.innerHTML = html;
        seccionHorarios.style.display = 'block';
        seccionHorarios.scrollIntoView({ behavior: 'smooth' });
    }
}

function generarOpcionesHora(horaSeleccionada = null, turno = 'manana') {
    let opciones = '';
    let horaInicio, horaFin;
    
    // Definir rangos según el turno
    switch(turno) {
        case 'manana':
            horaInicio = 6;
            horaFin = 14;
            break;
        case 'tarde':
            horaInicio = 12;
            horaFin = 22;
            break;
        case 'noche':
            horaInicio = 18;
            horaFin = 26; // 26 para incluir 2am del día siguiente
            break;
        default:
            horaInicio = 0;
            horaFin = 23;
    }
    
    for (let i = horaInicio; i <= horaFin; i++) {
        const horaReal = i >= 24 ? i - 24 : i; // Para manejar horas después de medianoche
        const hora = horaReal.toString().padStart(2, '0') + ':00';
        const selected = horaReal === horaSeleccionada ? 'selected' : '';
        opciones += `<option value="${horaReal}" ${selected}>${hora}</option>`;
    }
    return opciones;
}

window.scrollToConsultorio = function() {
    setTimeout(function() {
        const anchor = document.getElementById('scroll-anchor-consultorio');
        if (anchor) {
            anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }, 400); // Ajusta el tiempo si es necesario para esperar el render de Livewire
};

// Agregar validaciones específicas para los horarios
document.addEventListener('DOMContentLoaded', function() {
    // Escuchar cambios en los selects de horarios
    document.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('horarios_semanales')) {
            // Limpiar errores previos
            limpiarErrores();
            
            // Validar horarios después de un pequeño delay para permitir que Livewire actualice
            setTimeout(function() {
                validarSolapamientoHorarios();
            }, 100);
        }
    });
});
</script>

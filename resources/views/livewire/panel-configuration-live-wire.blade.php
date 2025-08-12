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

                <form id="frm-clinica-wizard">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombreClinica" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" wire:model="clinicaData.tnombre" id="inputNombreClinica"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputDireccionClinica" class="form-label">DIRECCIÓN</label>
                                <input type="text" class="form-control" wire:model="clinicaData.tdireccion"
                                    id="inputDireccionClinica">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputRfcClinica" class="form-label">RFC</label>
                                <input type="text" class="form-control" wire:model="clinicaData.vrfc" id="inputRfcClinica">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputTelefonoClinica" class="form-label">TELÉFONO</label>
                                <input type="text" class="form-control" wire:model="clinicaData.ttelefono"
                                    id="inputTelefonoClinica">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputFolioClinica" class="form-label">*FOLIO</label>
                                <input type="text" class="form-control" wire:model="clinicaData.vfolioclinica"
                                    id="inputFolioClinica" required>
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
                    <button class="btn btn-primary" wire:click="guardarClinica" 
                            {{ $this->botonHabilitado ? '' : 'disabled' }}>
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
                        <!-- Selector de días de la semana -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                        <button type="button" 
                                                wire:click="cambiarDia('{{ $dia }}')"
                                                class="btn {{ $dia_seleccionado === $dia ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                            {{ ucfirst($dia) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

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

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_inicio"
                                                min="6" max="12" step="1" value="6"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_fin" min="7"
                                                max="12" step="1" value="12"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_manana_inicio'] - 6) / 6) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_manana_fin'] - 6) / 6) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_manana_inicio'] - 6) / 6) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>6:00 AM</span>
                                            <span>12:00 PM</span>
                                        </div>

                                        <div class="range-values align-items-center">
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_manana_inicio']) }}</span>
                                            <span class="mx-2">a</span>
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_manana_fin']) }}</span>
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

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_inicio"
                                                min="13" max="18" step="1" value="13"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_fin" min="13"
                                                max="18" step="1" value="18"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio'] - 12) / 6) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_fin'] - 12) / 6) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio'] - 12) / 6) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>1:00 PM</span>
                                            <span>6:00 PM</span>
                                        </div>

                                        <div class="range-values">
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio']) }}</span>
                                            <span class="mx-2">a</span>
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_tarde_fin']) }}</span>
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

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_inicio"
                                                min="19" max="22" step="1" value="19"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_fin" min="19"
                                                max="22" step="1" value="22"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_noche_inicio'] - 18) / 4) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_noche_fin'] - 18) / 4) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_noche_inicio'] - 18) / 4) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>7:00 PM</span>
                                            <span>10:00 PM</span>
                                        </div>

                                        <div class="range-values">
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_noche_inicio']) }}</span>
                                            <span class="mx-2">a</span>
                                            <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_noche_fin']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col"></div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputRfcClinica" class="form-label">DURACIÓN CONSULTA</label>
                                    <select wire:model="consultoriosData.{{ $iConsultorio - 1 }}.duracion_consulta" class="form-control">
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
                            <h3 class="text-center mb-4 mt-3"><i class="fas fa-clock"></i> Configurar horarios por día</h3>
                            <!-- Selector de días de la semana -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center flex-wrap gap-2">
                                        @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia)
                                            <button type="button" 
                                                    wire:click="cambiarDia('{{ $dia }}')"
                                                    class="btn {{ $dia_seleccionado === $dia ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                                {{ ucfirst($dia) }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

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

                                        <div class="range-slider-container">
                                            <div class="range-slider">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_inicio"
                                                    min="6" max="12" step="1" value="6"
                                                    class="range-input-min">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_manana_fin" min="7"
                                                    max="12" step="1" value="12"
                                                    class="range-input-max">
                                                <div class="range-track"
                                                    style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_manana_inicio'] - 6) / 6) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_manana_fin'] - 6) / 6) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_manana_inicio'] - 6) / 6) * 100 }}% - 24px);">
                                                </div>
                                            </div>

                                            <div class="range-labels">
                                                <span>6:00 AM</span>
                                                <span>12:00 PM</span>
                                            </div>

                                            <div class="range-values align-items-center">
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_manana_inicio']) }}</span>
                                                <span class="mx-2">a</span>
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_manana_fin']) }}</span>
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

                                        <div class="range-slider-container">
                                            <div class="range-slider">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_inicio"
                                                    min="13" max="18" step="1" value="13"
                                                    class="range-input-min">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_tarde_fin" min="13"
                                                    max="18" step="1" value="18"
                                                    class="range-input-max">
                                                <div class="range-track"
                                                    style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio'] - 12) / 6) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_fin'] - 12) / 6) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio'] - 12) / 6) * 100 }}% - 24px);">
                                                </div>
                                            </div>

                                            <div class="range-labels">
                                                <span>1:00 PM</span>
                                                <span>6:00 PM</span>
                                            </div>

                                            <div class="range-values">
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_tarde_inicio']) }}</span>
                                                <span class="mx-2">a</span>
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_tarde_fin']) }}</span>
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

                                        <div class="range-slider-container">
                                            <div class="range-slider">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_inicio"
                                                    min="19" max="22" step="1" value="19"
                                                    class="range-input-min">
                                                <input type="range" wire:model.live="horarios_semanales.{{ $dia_seleccionado }}.turno_noche_fin" min="19"
                                                    max="22" step="1" value="22"
                                                    class="range-input-max">
                                                <div class="range-track"
                                                    style="left: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_noche_inicio'] - 18) / 4) * 100 }}% + 12px); width: calc({{ (($horarios_semanales[$dia_seleccionado]['turno_noche_fin'] - 18) / 4) * 100 }}% - {{ (($horarios_semanales[$dia_seleccionado]['turno_noche_inicio'] - 18) / 4) * 100 }}% - 24px);">
                                                </div>
                                            </div>

                                            <div class="range-labels">
                                                <span>7:00 PM</span>
                                                <span>10:00 PM</span>
                                            </div>

                                            <div class="range-values">
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_noche_inicio']) }}</span>
                                                <span class="mx-2">a</span>
                                                <span class="time-display">{{ $this->formatTime12Hour($horarios_semanales[$dia_seleccionado]['turno_noche_fin']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col"></div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="inputRfcClinica" class="form-label">DURACIÓN CONSULTA</label>
                                        <select wire:model="consultoriosData.{{ $iConsultorio - 1 }}.duracion_consulta" class="form-control">
                                            @foreach (config('enums.interval_hours') as $key => $itemHour)
                                                <option value="{{ $key }}">{{ $itemHour }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                            @if($iConsultorio == 1 && !$this->hayConsultorioValido) disabled @endif>
                            Continuar <i class="fas fa-arrow-right"></i>
                        </button>
                       
                    </div>
                </div>
            @endfor
        @endif
    </div>

</div>
<script>
    window.scrollToConsultorio = function() {
        setTimeout(function() {
            const anchor = document.getElementById('scroll-anchor-consultorio');
            if (anchor) {
                anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 400); // Ajusta el tiempo si es necesario para esperar el render de Livewire
    };
</script>

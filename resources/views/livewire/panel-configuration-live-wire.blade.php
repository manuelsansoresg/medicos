<div class="wizard-container mt-5">
    <div class="header">
        <h1><i class="fas fa-cogs"></i> Configura tu Panel</h1>
        <p>Configura tu panel para poder usar el sistema</p>
    </div>

    <div class="steps">
        {{-- si eligio clinica --}}
        @if ($typeConfiguration == 1)
            <div class="step" id="step1-clinic">
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
                <div class="step {{ $iConsultorio == 1 ? 'active' : '' }}" id="step2-cons-{{ $headTagNumConsultory }}">

                    <div class="step-number">{{ $headContConsultory }}</div>
                    <div class="step-title">Consultorio {{ $iConsultorio }}</div>
                    <div class="step-connector"></div>
                </div>
            @endfor

        @endif
    </div>

    <div class="form-content">
        {{-- si eligio clinica --}}
        @if ($typeConfiguration == 1)
            <div class="step-pane " id="step1-clinic">
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
                                <input type="text" class="form-control" name="data[tnombre]" id="inputNombreClinica"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputDireccionClinica" class="form-label">DIRECCIÓN</label>
                                <input type="text" class="form-control" name="data[tdireccion]"
                                    id="inputDireccionClinica">
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
                                <input type="text" class="form-control" name="data[ttelefono]"
                                    id="inputTelefonoClinica">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputFolioClinica" class="form-label">*FOLIO</label>
                                <input type="text" class="form-control" name="data[vfolioclinica]"
                                    id="inputFolioClinica" required>
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
                    <button class="btn btn-primary" id="next-to-step2" disabled>Continuar <i
                            class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        @endif
        {{-- consultorios si configuracion es 1 --}}
        <!-- Paso 2: Crear Consultorio (para type_configuration = 1) -->
        @for ($iConsultorio = 1; $iConsultorio <= $totalConsultorio; $iConsultorio++)
            @php
                $TagNumConsultory = $indiceConsultorio + 1;
                $ContConsultory = $iConsultorio + 1;
            @endphp
            <div class="step-pane active" id="step2-cons-{{ $TagNumConsultory }}">

                <h3 class="text-center mb-4"><i class="fas fa-stethoscope"></i> Configurar consultorio
                    {{ $iConsultorio }} de {{ $totalConsultorio }} </h3>

                <form id="frm-consultorio-wizard">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombreConsultorio" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" name="data[vnumconsultorio]"
                                    id="inputNombreConsultorio" required>
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
                                <input type="text" class="form-control" name="data[ttelefono]"
                                    id="inputTelefonoConsultorio">
                            </div>
                        </div>
                        <h3 class="text-center mb-4"><i class="fas fa-clock"></i> Configurar horarios</h3>

                        <!-- Sección de Horarios -->


                        <div class="row">
                            <div class="col-12">
                                <p class="text-info mb-4">Configura los rangos de horarios para tu consultorio. Puedes
                                    ajustar las horas de inicio y fin para cada período del día.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mt-3">
                                <!-- Horario Mañana -->
                                <div class="horario-section horario-manana">
                                    <div class="horario-header">
                                        <h4 class="horario-title"> <i class="fas fa-sun"></i> Horario de Mañana</h4>
                                    </div>

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horario_manana_inicio"
                                                min="6" max="12" step="1" value="6"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horario_manana_fin" min="7"
                                                max="12" step="1" value="12"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horario_manana_inicio - 6) / 6) * 100 }}% + 12px); width: calc({{ (($horario_manana_fin - 6) / 6) * 100 }}% - {{ (($horario_manana_inicio - 6) / 6) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>6:00 AM</span>
                                            <span>12:00 PM</span>
                                        </div>

                                        <div class="range-values align-items-center">

                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_manana_inicio) }}</span>
                                            <span class="mx-2">a</span>
                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_manana_fin) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-3">
                                <!-- Horario Tarde -->
                                <div class="horario-section horario-tarde">
                                    <div class="horario-header">

                                        <h4 class="horario-title"> <i class="fas fa-sun"></i> Horario de Tarde</h4>
                                    </div>

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horario_tarde_inicio"
                                                min="12" max="18" step="1" value="12"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horario_tarde_fin" min="13"
                                                max="18" step="1" value="18"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horario_tarde_inicio - 12) / 6) * 100 }}% + 12px); width: calc({{ (($horario_tarde_fin - 12) / 6) * 100 }}% - {{ (($horario_tarde_inicio - 12) / 6) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>12:00 PM</span>
                                            <span>6:00 PM</span>
                                        </div>

                                        <div class="range-values">
                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_tarde_inicio) }}</span>
                                            <span class="mx-2">a</span>
                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_tarde_fin) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-3">
                                <!-- Horario Noche -->
                                <div class="horario-section horario-noche">
                                    <div class="horario-header">

                                        <h4 class="horario-title"><i class="fas fa-moon"></i> Horario de Noche</h4>
                                    </div>

                                    <div class="range-slider-container">
                                        <div class="range-slider">
                                            <input type="range" wire:model.live="horario_noche_inicio"
                                                min="18" max="22" step="1" value="18"
                                                class="range-input-min">
                                            <input type="range" wire:model.live="horario_noche_fin" min="19"
                                                max="22" step="1" value="22"
                                                class="range-input-max">
                                            <div class="range-track"
                                                style="left: calc({{ (($horario_noche_inicio - 18) / 4) * 100 }}% + 12px); width: calc({{ (($horario_noche_fin - 18) / 4) * 100 }}% - {{ (($horario_noche_inicio - 18) / 4) * 100 }}% - 24px);">
                                            </div>
                                        </div>

                                        <div class="range-labels">
                                            <span>6:00 PM</span>
                                            <span>10:00 PM</span>
                                        </div>

                                        <div class="range-values">
                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_noche_inicio) }}</span>
                                            <span class="mx-2">a</span>
                                            <span
                                                class="time-display">{{ $this->formatTime12Hour($horario_noche_fin) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col"></div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputRfcClinica" class="form-label">DURACIÓN CONSULTA</label>
                                    <select name="" id="" class="form-control">
                                        @foreach (config('enums.interval_hours') as $key => $itemHour)
                                            <option value="{{ $key }}">{{ $itemHour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>






                    </div>
                </form>

                <div class="text-center btn-navigation">
                    <button class="btn btn-secondary mr-2" id="back-to-step1"><i class="fas fa-arrow-left"></i>
                        Anterior</button>
                    <button class="btn btn-primary">Continuar <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        @endfor
    </div>

</div>

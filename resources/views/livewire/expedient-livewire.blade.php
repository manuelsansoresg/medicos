<div class="container bg-white py-2">
    <div class="row">
        <div class="col-12">
            <form id="frm-download-expedient">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="setClinica" class="form-label">*CLINICAS</label>
                            <select name="clinica" id="selectClinic" class="form-control" wire:model="clinica">
                                <option value="">Seleccione una opción</option>
                                @foreach ($my_clinics as $my_clinic)
                                    @php
                                        $clinica = $my_clinic->clinica;
                                    @endphp
                                    <option value="{{ $clinica->idclinica }}">
                                        {{ $clinica->tnombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="setConsultorio" class="form-label">*CONSULTORIOS</label>
                            <select name="consultorio" id="selectConsultory" class="form-control" wire:model="consultorio">
                                <option value="">Seleccione una opción</option>
                                @foreach ($my_consultories as $my_consultory)
                                    @php
                                        $consultory = $my_consultory->consultorio;
                                    @endphp
                                    <option value="{{ $consultory->idconsultorios }}">
                                        {{ $consultory->vnumconsultorio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="setConsultorio" class="form-label">FECHA INICIO</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" wire:model="fecha_inicio">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="setConsultorio" class="form-label">FECHA FINAL</label>
                            <input type="date" name="fecha_final" id="fecha_final" class="form-control" wire:model="fecha_final">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar por nombre o código" wire:model="search">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 text-end">
                        <button type="submit" id="btn-download-expedient" class="btn btn-primary" style="display: none;">
                            <i class="fas fa-download"></i> Descargar Expedientes
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll" value="1">
                                        </div>
                                    </td>
                                    <td>NOMBRE</td>
                                    <td>CÓDIGO PACIENTE</td>
                                    <td>ACCIONES</td>
                                </tr>
                                @foreach ($pacientes as $paciente)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input selectExpedient" name="expedients[]" value="{{ $paciente->id }}">
                                            </div>
                                        </td>
                                        <td> 
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-primary me-2 toggle-collapse" type="button" data-bs-target="#paciente{{ $paciente->id }}" aria-expanded="false" aria-controls="paciente{{ $paciente->id }}">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                                {{ $paciente->id }} - {{ $paciente->name }} {{ $paciente->vapellido }}
                                            </div>
                                        </td>
                                        <td>{{ $paciente->codigo_paciente }}</td>
                                        <td>
                                            @php
                                                $nombre = $paciente->name.' '. $paciente->vapellido;
                                            @endphp
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="p-0">
                                            <div class="collapse" id="paciente{{ $paciente->id }}">
                                                <div class="card card-body">
                                                    <div class="row">
                                                        <!-- Consultas -->
                                                        <div class="col-md-6">
                                                            <h5 class="mb-3">Consultas</h5>
                                                            @php
                                                                $consultas = App\Models\Consulta::getByPaciente($paciente->id);
                                                            @endphp
                                                            @if($consultas->count() > 0)
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Fecha</th>
                                                                                <th>Detalles</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($consultas as $consulta)
                                                                                @php
                                                                                    $fields = App\Models\FormularioEntryField::getFields($consulta->id);
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                                                                                    <td>
                                                                                        <div class="consulta-details">
                                                                                            @foreach($fields as $field)
                                                                                                <div class="field-item">
                                                                                                    <strong>{{ $field['name'] }}:</strong> 
                                                                                                    <span>{{ $field['value'] }}</span>
                                                                                                </div>
                                                                                            @endforeach
                                                                                            @if($consulta->peso || $consulta->estatura)
                                                                                                <div class="field-item">
                                                                                                    <strong>Medidas:</strong>
                                                                                                    <span>
                                                                                                        @if($consulta->peso)
                                                                                                            Peso: {{ $consulta->peso }} kg
                                                                                                        @endif
                                                                                                        @if($consulta->estatura)
                                                                                                            Estatura: {{ $consulta->estatura }} cm
                                                                                                        @endif
                                                                                                    </span>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <p class="text-muted">No hay consultas registradas</p>
                                                            @endif
                                                        </div>
    
                                                        <!-- Estudios -->
                                                        <div class="col-md-6">
                                                            <h5 class="mb-3">Estudios</h5>
                                                            @php
                                                                $estudios = App\Models\Estudio::getByPaciente($paciente->id);
                                                            @endphp
                                                            @if($estudios->count() > 0)
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Fecha</th>
                                                                                <th>Estudios</th>
                                                                                <th>Diagnósticos</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($estudios as $estudio)
                                                                                <tr>
                                                                                    <td>{{ \Carbon\Carbon::parse($estudio->created_at)->format('d/m/Y') }}</td>
                                                                                    <td>{{ $estudio->estudios }}</td>
                                                                                    <td>{{ $estudio->diagnosticos }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <p class="text-muted">No hay estudios registrados</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {{ $pacientes->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



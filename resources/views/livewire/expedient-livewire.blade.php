<div class="container bg-white py-2">
    <div class="row">
        <div class="col-12">
            <form id="frm-download-expedient">
                <div class="row align-items-end">
                    @hasrole(['administrador', 'medico', 'auxiliar', 'secretario', 'paciente'])
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="setClinica" class="form-label">*CLINICAS</label>
                            <select name="clinica" id="selectClinic" class="form-control" wire:model="clinica">
                                <option value="">Seleccione una opción</option>
                                @foreach ($my_clinics as $clinica)
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
                                @foreach ($my_consultories as $consultorio)
                                    <option value="{{ $consultorio->idconsultorios }}">
                                        {{ $consultorio->vnumconsultorio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endhasrole
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
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll" value="1">
                                            </div>
                                        </th>
                                        <th>PACIENTE</th>
                                        <th>CÓDIGO</th>
                                        <th>TOTAL CITAS</th>
                                        <th>CONSULTAS</th>
                                        <th>ESTUDIOS</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($paginator->count() > 0)
                                        @foreach ($paginator as $paciente)
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
                                                    <strong>{{ $paciente->name }} {{ $paciente->vapellido }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $paciente->codigo_paciente }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $paciente->citas->count() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $paciente->total_consultas }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $paciente->total_estudios }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="window.open('{{ url('admin/expedientes/' . $paciente->id) }}', '_blank')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="p-0">
                                                <div class="collapse" id="paciente{{ $paciente->id }}">
                                                    <div class="card card-body">
                                                        <h6 class="mb-3">Historial de Citas</h6>
                                                        @foreach($paciente->citas as $cita)
                                                            <div class="cita-item mb-4 border rounded p-3">
                                                                <div class="cita-header d-flex justify-content-between align-items-center mb-3">
                                                                    <h6 class="mb-0">
                                                                        <i class="fas fa-calendar-alt text-primary"></i>
                                                                        Cita del {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} 
                                                                        a las {{ $cita->hora }}
                                                                    </h6>
                                                                    <div class="cita-info">
                                                                        @if($cita->clinica)
                                                                            <span class="badge bg-secondary me-1">{{ $cita->clinica->tnombre }}</span>
                                                                        @endif
                                                                        @if($cita->consultorio)
                                                                            <span class="badge bg-secondary">{{ $cita->consultorio->vnumconsultorio }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($cita->motivo)
                                                                    <div class="mb-2">
                                                                        <strong>Motivo:</strong> {{ $cita->motivo }}
                                                                    </div>
                                                                @endif

                                                                <div class="row">
                                                                    <!-- Consultas de esta cita -->
                                                                    <div class="col-md-6">
                                                                        <h6 class="text-success">
                                                                            <i class="fas fa-stethoscope"></i> Consultas ({{ $cita->consultas->count() }})
                                                                        </h6>
                                                                        @if($cita->consultas->count() > 0)
                                                                            <div class="table-responsive">
                                                                                <table class="table table-sm">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Fecha</th>
                                                                                            <th>Detalles</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($cita->consultas as $consulta)
                                                                                            @php
                                                                                                $fields = App\Models\FormularioEntryField::getFields($consulta->id);
                                                                                            @endphp
                                                                                            <tr>
                                                                                                <td>{{ \Carbon\Carbon::parse($consulta->created_at)->format('d/m/Y') }}</td>
                                                                                                <td>
                                                                                                    <div class="consulta-details">
                                                                                                        @foreach($fields as $field)
                                                                                                            <div class="field-item small">
                                                                                                                <strong>{{ $field['name'] }}:</strong> 
                                                                                                                <span>{{ $field['value'] }}</span>
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        @else
                                                                            <p class="text-muted small">No hay consultas registradas para esta cita</p>
                                                                        @endif
                                                                    </div>

                                                                    <!-- Estudios de esta cita -->
                                                                    <div class="col-md-6">
                                                                        <h6 class="text-info">
                                                                            <i class="fas fa-microscope"></i> Estudios ({{ $cita->estudios->count() }})
                                                                        </h6>
                                                                        @if($cita->estudios->count() > 0)
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
                                                                                        @foreach($cita->estudios as $estudio)
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
                                                                            <p class="text-muted small">No hay estudios registrados para esta cita</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    No se encontraron expedientes con los filtros aplicados.
                                                    <br>
                                                    <small>Intente ajustar los filtros de fecha, clínica o consultorio.</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {{ $paginator->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.cita-item {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff !important;
}

.cita-header {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
}

.field-item {
    margin-bottom: 0.25rem;
}

.consulta-details {
    max-height: 200px;
    overflow-y: auto;
}

.table-sm td, .table-sm th {
    padding: 0.25rem;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar selección de todos los expedientes
    const selectAll = document.getElementById('selectAll');
    const selectExpedients = document.querySelectorAll('.selectExpedient');
    const downloadBtn = document.getElementById('btn-download-expedient');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            selectExpedients.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDownloadButton();
        });
    }

    // Manejar selección individual
    selectExpedients.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateDownloadButton();
            updateSelectAll();
        });
    });

    function updateDownloadButton() {
        const checkedBoxes = document.querySelectorAll('.selectExpedient:checked');
        if (checkedBoxes.length > 0) {
            downloadBtn.style.display = 'inline-block';
        } else {
            downloadBtn.style.display = 'none';
        }
    }

    function updateSelectAll() {
        const checkedBoxes = document.querySelectorAll('.selectExpedient:checked');
        const totalBoxes = selectExpedients.length;
        
        if (selectAll) {
            selectAll.checked = checkedBoxes.length === totalBoxes;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
        }
    }

    // Manejar descarga de expedientes seleccionados
    const form = document.getElementById('frm-download-expedient');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.selectExpedient:checked');
            if (checkedBoxes.length === 0) {
                alert('Por favor seleccione al menos un expediente para descargar.');
                return;
            }

            // Crear formulario para descarga
            const downloadForm = document.createElement('form');
            downloadForm.method = 'POST';
            downloadForm.action = '{{ url("admin/expedientes/select/download") }}';
            downloadForm.target = '_blank';

            // Agregar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            downloadForm.appendChild(csrfToken);

            // Agregar expedientes seleccionados
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'expedients[]';
                input.value = checkbox.value;
                downloadForm.appendChild(input);
            });

            document.body.appendChild(downloadForm);
            downloadForm.submit();
            document.body.removeChild(downloadForm);
        });
    }

    // Inicializar estado del botón
    updateDownloadButton();
});
</script>



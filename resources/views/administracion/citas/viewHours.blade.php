<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            @if ($fechasEspeciales == true)
                <strong>Lo sentimos el d&iacute;a de hoy el medico seleccionado no tiene consultas</strong>
            @endif
        </div>
        @php
            $horarios = [0 => 'Mañana', 1 => 'TARDE', 2 => 'NOCHE'];
        @endphp

    </div>
    @if ($fechasEspeciales == false)
        <div class="row">
            @foreach ($consultaAsignados as $key => $consultaAsignado)
                <div class="col-12 col-md-4">
                    <div class="card py-2 px-2">
                        <div class="card-header">
                            <h6> {{ $horarios[$key] }} {{ $consultaAsignado['consultorio'] }} </h6>
                          </div>
                          <div class="card-body">
                            @php
                                $horariosAsignados = $consultaAsignado['horarios'];
                            @endphp
                            <div class="row">
                                <div class="col-6">Hora</div>
                                <div class="col-6">Status</div>
                            </div>
                            @foreach ($horariosAsignados as $horario)
                                <div class="row">
                                    <div class="col-6">{{ $horario['hora'] }} </div>
                                    <div class="col-6">
                                        
                                        @if ($horario['statusconactivanop'] == false)
                                            <a onclick="setParamAddCita({{ $consultaAsignado['id'] }}, '{{ $horario['hora'] }}')" href="#">Disponible  </a>
                                        @else
                                        <a class="text-danger" href="#" onclick="liberarCita({{ $horario['user_cita_id'] }})">Ocupado</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                          </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

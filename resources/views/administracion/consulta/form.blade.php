@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"><a href="/admin/actividades">ACTIVIDADES</a></li>
                        <li class="breadcrumb-item active"> <a href="/admin/citas/{{ $consultaAsignadoId }}/list">CITAS</a>
                        </li>
                        <li class="breadcrumb-item active">CONSULTA</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3">
            <div class="col-12">
                <div class="row">
                    <div class="col-2">
                        <i class="fas fa-user fa-4x"></i>
                    </div>
                    <div class="col-10">
                        <h6>Nombre: {{ $paciente->name }} {{ $paciente->fecha_nacimiento }}</h6>
                        <h6>Peso:
                            @if ($ultimaConsulta != null)
                                {{ $ultimaConsulta->peso }} ({{ $ultimaConsulta->created_at }})
                            @endif
                        </h6>
                        <h6>Alergias: {{ $paciente->alergias }} </h6>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-consulta"
                            type="button" role="tab" aria-controls="nav-consulta" aria-selected="true">
                           
                            CONSULTAS</button>
                        <button class="nav-link" id="nav-estudio-tab" data-bs-toggle="tab" data-bs-target="#nav-estudio"
                            type="button" role="tab" aria-controls="nav-estudio" aria-selected="false">ESTUDIOS</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-consulta" role="tabpanel" aria-labelledby="nav-consulta-tab">
                        <div class="col-12 mt-3 py-3">
                            <a href="#" onclick="nuevaConsulta('{{ $ultimaConsulta->peso }}')" class="btn btn-primary float-right">Nueva consulta</a>
                        </div>
                        <div class="row" id="content-consulta" style="display: none">
                            <div class="col-12">
                                <form method="post" action="/admin/consulta" id="frm-consulta">
                                    <div class="col-12">
                                        <p class="text-info">Los campos marcados con * son requeridos</p>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputRecordatorio" class="form-label">*MOTIVO DE LA VISITA</label>
                                                <input type="text" name="data[motivo]" id="motivo" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputPeso" class="form-label">PESO</label>
                                                <input type="text" name="data[peso]" id="peso" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputExploracion" class="form-label">EXPLORACIÓN MEDICA</label>
                                                <textarea name="data[exploracion]" id="exploracion" cols="30" rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputReceta" class="form-label">RECETA MEDICA</label>
                                                <textarea name="data[receta]" id="receta" cols="30" rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="data[user_cita_id]" id="user_cita_id" value="{{ $userCitaId }}">
                                        <input type="hidden" name="data[paciente_id]" id="paciente_id" value="{{ $paciente != null ? $paciente->id : null }}">
                                        <input type="hidden" name="consulta_id" id="consulta_id" value="">
                                        <div class="mb-3 text-right">
                                            <button class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                    @csrf
    
                                </form>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            
                            <livewire:consulta-livewire :limit="10" :pacienteId="$paciente->id" />


                            
                        </div>
                    </div>
                    
                    <div class="tab-pane fade show active" id="nav-estudio" role="tabpanel" aria-labelledby="nav-estudio-tab">
                    </div>
                </div>
            </div>
            <input type="hidden" id="consultaAsignadoId" value="{{ $consultaAsignadoId }}">
            <div class="col-12 mt-3">
                <form method="post" action="/admin/usuarios" id="frm-pendiente">
                    {{--  @if ($user_id == null)
            @else
            <form method="post" action="/admin/usuarios" id="upd-frm-users">
            @endif --}}
                    {{-- <div class="col-12">
                    <p class="text-info">Los campos marcados con * son requeridos</p>
                </div> --}}

                    @csrf
                    <div class="row">
                        {{--  <div class="col-12">
                        <div class="mb-3">
                            <label for="inputRecordatorio" class="form-label">*RECORDATORIO</label>
                            <textarea name="data[pendiente]" id="inputRecordatorio" cols="30" rows="4" class="form-control" required>{{ $pendiente != null ? $pendiente->pendiente : null }}</textarea>
                        </div>
                    </div> --}}



                        {{-- <div class="col-md-12 text-right">
                            <div class="mb-3">
                                <input type="hidden" id="pendiente_id" name="pendiente_id" value="{{ $pendiente_id }}" >
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </div> --}}
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

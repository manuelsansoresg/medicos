@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"><a href="/admin/actividades">ACTIVIDADES</a></li>
                        <li class="breadcrumb-item active"> <a href="/admin/citas/{{ $consultaId }}/list">CITAS</a>
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
                        <h6>Alergias: {{ $paciente->alergias }} </h6>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-consulta-tab" data-bs-toggle="tab" data-bs-target="#nav-consulta"
                        type="button" role="tab" aria-controls="nav-consulta" aria-selected="true" onclick="updateSelectedTab('consultas')">
                       
                        CONSULTAS</button>
                    <button class="nav-link" id="nav-estudio-tab" data-bs-toggle="tab" data-bs-target="#nav-estudio"
                        type="button" role="tab" aria-controls="nav-estudio" aria-selected="false"onclick="updateSelectedTab('estudios')">ESTUDIOS</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-consulta" role="tabpanel" aria-labelledby="nav-consulta-tab">
                        <div class="col-12 mt-3 py-3">
                            <a href="#" onclick="nuevaConsulta()" class="btn btn-primary float-right">Nueva consulta</a>
                        </div>
                        <div class="row" id="content-consulta" style="display: none">
                            <div class="col-12">
                                @if ($totalTemplates == 0) {{-- si es 0 indicar que falta asignar un template --}}
                                    <h5>Sin plantilla configurada</h5>
                                    <input type="hidden" id="myTemplate" value="null">
                                @else
                                    
                                @if ($totalTemplates > 1) {{-- es admin y tiene varias plantillas activas --}}
                                    <input type="hidden" id="myTemplate" value="null">
                                    <div class="col-12">
                                        <div class="mb-3" id="selectPlantilla">
                                            <label for="inputRecordatorio" class="form-label">*SELECCIONAR PLANTILLA</label>
                                            <select name="" id="templateConsulta" class="form-control select2multiple" onchange="changeTemplateConsulta()">
                                                <option value="">Seleccione una opción</option>
                                                @foreach ($myTemplates as $myTemplate)
                                                    <option value="{{ $myTemplate->id }}">{{ $myTemplate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12" id="content-form-template">

                                        </div>
                                    </div>
                                    @else {{-- es un usuario no admin y tiene una plantilla activa --}}
                                    <input type="hidden" id="myTemplate" value="{{ $myTemplates->id }}">
                                    <input type="hidden" id="templateConsulta">
                                    <div class="col-12" id="content-form-template">

                                    </div>
                                    @endif
                                @endif
                                <form method="post" action="/admin/consulta" id="frm-consulta" style="display: none">
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
                                                <label for="inputPeso" class="form-label">TEMPERATURA</label>
                                                <input type="text" name="data[temperatura]" id="temperatura" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputPeso" class="form-label">ESTATURA</label>
                                                <input type="text" name="data[estatura]" id="estatura" class="form-control">
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
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputReceta" class="form-label">INDICACIONES GENERALES</label>
                                                <textarea name="data[indicaciones_generales]" id="indicaciones_generales" cols="30" rows="4" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="data[user_cita_id]" id="user_cita_id" value="{{ $userCitaId }}">
                                        <input type="hidden"  id="user_cita_id_origin" value="{{ $userCitaId }}">
                                        <input type="hidden" name="data[paciente_id]" id="paciente_id" value="{{ $paciente != null ? $paciente->id : null }}">
                                        <input type="hidden" name="consulta_id" id="consulta_id" value="">
                                        <div class="mb-3 text-end">
                                            <a class="btn btn-danger pointer" onclick="cancelConsulta()">Cancelar</a>
                                            <button class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                    @csrf
    
                                </form>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <input type="hidden" id="origin_user_cita_id" value="{{ $userCitaId }}">
                            <input type="hidden" id="origin_user_cita_id_origin" value="{{ $userCitaId }}">
                            <input type="hidden" id="origin_paciente_id" value="{{ $paciente != null ? $paciente->id : null }}">

                            <livewire:consulta-livewire :limit="10" :pacienteId="$paciente->id" :isExpedient="$isExpedient"/>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade show active" id="nav-estudio" role="tabpanel" aria-labelledby="nav-estudio-tab">
                        <div class="col-12 mt-3 py-3" id="content-nuevo-estudio">
                            <a href="#" onclick="nuevoEstudio()" class="btn btn-primary float-right">Nuevo estudio</a>
                        </div>
                        <div class="row" id="content-estudio" style="display: none">
                           
                            <div class="col-12">
                                <form method="post" action="/admin/estudio" id="frm-estudio">
                                    <div class="col-12">
                                        <p class="text-info">Los campos marcados con * son requeridos</p>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputRecordatorio" class="form-label">*ESTUDIOS</label>
                                                <textarea name="data[estudios]" id="estudios" cols="30" rows="4" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="inputPeso" class="form-label">DIAGNOSTICOS</label>
                                                <textarea name="data[diagnosticos]" id="diagnosticos" cols="30" rows="4" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="data[user_cita_id]" id="estudio_user_cita_id" value="{{ $userCitaId }}">
                                        <input type="hidden" name="data[paciente_id]" id="estudio_paciente_id" value="{{ $paciente != null ? $paciente->id : null }}">
                                        <input type="hidden" name="estudio_id" id="estudio_diagnostico_id" value="">
                                        <div class="mb-3 text-end">
                                            <a class="btn btn-danger pointer" onclick="cancelEstudio()">Cancelar</a>
                                            <button class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                    @csrf
    
                                </form>
                            </div>
                        </div>
                      
                        <div class="col-12 mt-5">
                            @livewire('estudio-livewire', ['limit' => 10, 'pacienteId' => $paciente->id, 'userCitaId' => $userCitaId, 'ConsultaAsignado' => $consultaId, 'isExpedient' => $isExpedient ])

                            
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="consultaAsignadoId" value="{{ $consultaId }}">
            
        </div>
    </div>


@endsection

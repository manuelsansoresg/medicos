@extends('layouts.template')

@section('content')
@inject('CUser', 'App\Models\User') 
<div class="container bg-white py-2">
    <div class="row mt-3 card">
       
        <div class="card-body">

            <div class="col-12 mt-3">
                <form method="post"  id="frm-solicitud">
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">*SOLICITUD</label>
                                <select name="data[solicitud_origin_id]" id="solicitud_origin_id" class="form-control" required onchange="setSolicitud(this)">
                                    <option value="">Selecciona una opción</option>
                                    @hasrole(['administrador'])
                                        <option value="0">Paquete</option>
                                    @endrole
                                    @foreach ($catalogPrices as $catalogPrice)
                                        <option value="{{ $catalogPrice->id }}">{{  $catalogPrice->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputApellido" class="form-label">*CANTIDAD</label>
                                <input type="number" class="form-control" name="data[cantidad]" id="cantidad" min="1" max="50"  value="{{ $query != null ? $query->cantidad : null }}" required>
                            </div>
                        </div>
                        
                        @hasrole(['administrador'])
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputApellido" class="form-label">*USUARIO</label>
                                    <select name="data[usuario_principal]" id="usuario_principal" class="form-control select2multiple">
                                        @foreach ($users as $users)
                                            <option value="{{ $users->id }}" {{ $query != null && $users->id == $query->user_id ? 'selected' : null }}>{{ $users->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endrole
                        <div id="content-solicitud-pacientes" style="display: none">
                            <h5 class="color-secondary mt-3">Seleccione un paciente</h5>
                            <livewire:paciente-livewire :limit="50" :isList="true" :isShowDownload="false" :isOriginSolicitud="true" />
                            <input type="hidden" id="pacienteId" name="pacientes_ids" value="">

                        </div>

                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <input type="hidden" id="solicitudId" name="solicitudId" value="{{ $query != null ? $query->id : null }}" >
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- modal permisos --}}
@php
    
@endphp
<div class="modal fade" id="modalPermisosPaciente" tabindex="-1" aria-labelledby="modalPermisosPacienteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="frm-config-download-pacient-expedient">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPermisosPacienteLabel">Agregar permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="content-pacient-permissions">

                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
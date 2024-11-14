@extends('layouts.template')

@section('content')
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
                                <select name="data[catalog_prices_id]" id="catalog_prices_id" class="form-control" required onchange="setSolicitud(this)">
                                    <option value="">Selecciona una opción</option>
                                    @foreach ($catalogPrices as $catalogPrice)
                                        <option value="{{ $catalogPrice->id }}">{{  $catalogPrice->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputApellido" class="form-label">*CANTIDAD</label>
                                <input type="number" class="form-control" name="data[cantidad]" id="cantidad" min="1" max="null" value="{{ $query != null ? $query->cantidad : null }}" required>
                            </div>
                        </div>
                        <div id="content-solicitud-pacientes">
                            <h5 class="color-secondary mt-3">Seleccione un paciente</h5>
                            <livewire:paciente-livewire :limit="50" :isList="true" :isShowDownload="false" :isOriginSolicitud="true" />
                            <input type="hidden" id="pacienteId" id="data[paciente_id]" value="">

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
@endsection
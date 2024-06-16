@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"> <a href="/admin/clinica">LISTA DE CLINICAS</a> </li>
                        <li class="breadcrumb-item">CLINICA</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div class="container bg-white py-2 px-2">

        <div class="row mt-3">

            <div class="col-12 mt-3">
                <form id="frm-clinica">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" name="data[tnombre]" id="inputNombre"
                                    value="{{ $clinica != null ? $clinica->tnombre : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputDireccion" class="form-label">DIRECCIÓN</label>
                                <input type="text" class="form-control" name="data[tdireccion]" id="inputDireccion"
                                    value="{{ $clinica != null ? $clinica->tdireccion : null }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputRfc" class="form-label">RFC</label>
                                <input type="text" class="form-control" name="data[vrfc]" id="inputRfc"
                                    value="{{ $clinica != null ? $clinica->vrfc : null }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputTelefono" class="form-label">TELÉFONO</label>
                                <input type="text" class="form-control" name="data[ttelefono]" id="inputTelefono"
                                    value="{{ $clinica != null ? $clinica->ttelefono : null }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputFolio" class="form-label">*FOLIO</label>
                                <input type="text" class="form-control" name="data[vfolioclinica]" id="inputFolio"
                                    value="{{ $clinica != null ? $clinica->vfolioclinica : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputEstatus" class="form-label">*ACTIVO</label>
                                <select name="data[istatus]" id="inputEstatus" class="form-control" required>

                                    @foreach (config('enums.status') as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $clinica != null && $clinica->istatus == $key ? 'selected' : null }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="mb-3">
                                <input type="hidden" id="clinica_id" name="clinica_id" value="{{ $clinica_id }}">
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

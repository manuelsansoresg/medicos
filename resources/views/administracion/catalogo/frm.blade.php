@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/consultorio">LISTA DE CONSULTORIOS</a> </li>
                    <li class="breadcrumb-item">CONSULTORIO</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop
@section('content')
<div class="container bg-white py-2">

    <div class="row mt-3 card">
        
        <div class="card-body">

            <div class="col-12 mt-3">
                <form id="frm-catalogo">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" name="data[nombre]" id="inputNombre" value="{{ $query != null ? $query->nombre : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputPrecio" class="form-label">*PRECIO</label>
                                <input type="number" name="data[precio]" id="inputPrecio" class="form-control"
                                       value="{{ $query != null ? ($query->precio) : null }}"
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputPorcentajeGanancia" class="form-label">PORCENTAJE GANANCIAS</label>
                                <input type="number" class="form-control" name="data[porcentaje_ganancia]" 
                                       id="inputPorcentajeGanancia" 
                                       value="{{ $query != null ? ($query->porcentaje_ganancia) : null }}"
                                       min="0" step="0.01">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputDescripcion" class="form-label">DESCRIPCIÓN</label>
                                <textarea name="data[descripcion]" id="inputDescripcion" cols="30" rows="4" class="form-control">{{ $query != null ? ($query->descripcion) : null }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputEstatus" class="form-label">*ACTIVO</label>
                                <select name="data[status]" id="inputEstatus" class="form-control" required>

                                    @foreach (config('enums.status') as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $query != null && $query->status == $key ? 'selected' : null }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <input type="hidden" name="id" value="{{ $id }}" >
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
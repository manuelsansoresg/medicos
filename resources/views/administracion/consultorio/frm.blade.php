@extends('adminlte::page')

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

    <div class="row mt-3">
        
    
        <div class="col-12 mt-3">
            <form id="frm-consultorio">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label">NOMBRE</label>
                            <input type="text" class="form-control" name="data[vnumconsultorio]" id="inputNombre" value="{{ $query != null ? $query->vnumconsultorio : null }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputUbicacion" class="form-label">UBICACIÓN</label>
                            <textarea name="data[thubicacion]" id="inputUbicacion" cols="30" rows="3" class="form-control">{{ $query != null ? $query->thubicacion : null }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefono" class="form-label">TELÉFONO</label>
                            <input type="text" class="form-control" name="data[ttelefono]" id="inputTelefono" value="{{ $query != null ? $query->ttelefono : null }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputClinica" class="form-label">CLINICA</label>
                            <select name="data[idclinica]" id="inputClinica" class="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach ($clinicas as $clinica)
                                    <option value="{{ $clinica->idclinica }}" {{ $query!= null && $query->idclinica == $clinica->idclinica ? 'selected' : null }}>{{ $clinica->tnombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <div class="mb-3">
                            <input type="hidden" id="consultorio_id" name="consultorio_id" value="{{ $id }}" >
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>


@endsection
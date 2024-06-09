@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/sin_citas">LISTA</a> </li>
                    <li class="breadcrumb-item">SIN CITAS</li>
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
            <form id="frm-sincitas">
                
                @csrf
                <div class="row">
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label"> *FECHA INICIAL</label>
                            <input type="date" class="form-control" name="data[dfecha]" value="{{ $query != null ? $query->dfecha : null }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label"> *FECHA FINAL</label>
                            <input type="date" class="form-control" name="data[dfechafin]" value="{{ $query != null ? $query->dfechafin : null }}" required>
                        </div>
                    </div>
                    @if ($consultorio == 0)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label"> *CONSULTORIO </label>
                            <select name="data[idconsultorio]" id="" class="form-control" required>
                                @foreach ($consultorios as $getConsultorio)
                                    <option value="{{ $getConsultorio->idconsultorios }}" {{ $query != null && $getConsultorio->idconsultorios == $query->idconsultorios ? 'selected': null }}> {{ $getConsultorio->vnumconsultorio }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                   
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label"> MOTIVO</label>
                            <input type="text" class="form-control" name="data[tmotivo]" value="{{ $query != null ? $query->tmotivo : null }}" required>
                        </div>
                    </div>
                  
                    <div class="col-md-12 text-right">
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


@endsection
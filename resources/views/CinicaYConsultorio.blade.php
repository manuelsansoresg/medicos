@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3 px-1 px-md-5">
       
    
        <div class="col-12 mt-3">
            <form method="post"  id="frm-selection">
                <div class="col-12">
                    <p class="text-info">Favor de elegir una clinica y consultorio</p>
                </div>
               
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label">*CLINICAS</label>
                            <select name="clinica" id="setClinica"  onchange="changeConsultorio()" class="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach ($my_clinics as $my_clinic)
                                @php
                                    $clinica = $my_clinic->clinica;
                                @endphp
                                    <option value="{{ $clinica->idclinica }}">{{ $clinica->tnombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputApellido" class="form-label">*CONSULTORIOS</label>
                            <select name="consultorio" id="setConsultorio" disabled  class="form-control">
                                <option value="">Seleccione una opción</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 text-right">
                        <div class="mb-3">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    
                </div>
                
            </form>
        </div>
    </div>
</div>


<input type="hidden" id="isRedirect">
@endsection
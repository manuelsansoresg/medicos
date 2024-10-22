@extends('layouts.clinica_consultorio')

@section('content')
    {{-- <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div> --}}

    <div class="container ">
        <div class="row mt-3 px-1 px-md-5 justify-content-center">
           
        
            <div class="col-12 col-md-6 mt-3">

                
                <div class="card card-outline card-primary">


                    <div class="card-header ">
                        <h3 class="card-title float-none text-center">
                            FAVOR DE ELEGIR UNA CLINICA Y UN CONSULTORIO </h3>
                    </div>
                
                
                    <div class="card-body login-card-body ">
                        <form method="post"  id="frm-selection">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="inputNombre" class="form-label">*CLINICAS</label>
                                        <select name="clinica" id="setClinica"  onchange="changeConsultorio()" class="form-control" required>
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
                                        <select name="consultorio" id="setConsultorio" disabled  class="form-control" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                
                                <div class="col-md-12 text-end">
                                    <div class="mb-3">
                                        <a href="/logout" class="btn btn-danger">Salir</a>
                                        <button class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                
                
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <input type="hidden" id="isRedirect">

@endsection


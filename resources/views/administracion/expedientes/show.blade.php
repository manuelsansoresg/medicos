@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"><a href="/admin/expedientes">EXPEDIENTES</a></li>
                        <li class="breadcrumb-item active">EXPEDIENTE</li>
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
                       
                        
                        <div class="col-12 mt-5" wire:ignore>
                            <livewire:consulta-livewire :limit="10" :pacienteId="$paciente->id" :isExpedient="$isExpedient"/>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade show active" id="nav-estudio" role="tabpanel" aria-labelledby="nav-estudio-tab">
                       
                        <div class="col-12 mt-5" wire:ignore>
                            <livewire:estudio-livewire :limit="10" :pacienteId="$paciente->id" :isExpedient="$isExpedient"/>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>


@endsection

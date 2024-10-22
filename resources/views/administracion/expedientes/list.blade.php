@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">EXPEDIENTES</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3 justify-content-center">
        <div class="col-12 col-md-8">
            <livewire:paciente-livewire :limit="50" :isList="true" />
        </div>
    </div>
</div>
@stop
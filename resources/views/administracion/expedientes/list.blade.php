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
{{-- <livewire:paciente-livewire :limit="50" :isList="true" /> --}}
<livewire:expedient-livewire :limit="50"  />
@stop
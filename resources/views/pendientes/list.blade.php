@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item " >PENDIENTES</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="fw-bold">PANEL DE ADMINISTRACIÓN DE PENDIENTES</p>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/alarm.png') }}" alt="">
                    <p class="mt-2 text-center">PENDIENTES</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/addalarm.png') }}" alt="">
                    <p class="mt-2 text-center">NUEVO PENDIENTE</p>
                </a>
            </div>
            
        </div>
    </div>
@endsection
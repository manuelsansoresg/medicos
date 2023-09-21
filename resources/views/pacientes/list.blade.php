@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item " >PACIENTES DEL SISTEMA</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="fw-bold">PANEL DE ADMINISTRACIÓN DE PACIENTES</p>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/youth.png') }}" alt="">
                    <p class="mt-2 text-center">PACIENTES</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/follow.png') }}" alt="">
                    <p class="mt-2 text-center"> NUEVO PACIENTE</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/search.png') }}" alt="">
                    <p class="mt-2 text-center">BUSCAR PACIENTE</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/customer-service.png') }}" alt="">
                    <p class="mt-2 text-center"> CONSULTA TELEFONICA </p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/health-check.png') }}" alt="">
                    <p class="mt-2 text-center">  CONSULTA DIRECTA </p>
                </a>
            </div>
        </div>
    </div>
@endsection
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
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item " >ADMINISTRACIÓN</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="fw-bold">PANEL DE ADMINISTRACIÓN</p>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/clinica" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/hospital.png') }}" alt="">
                    <p class="mt-2 text-center">CLINICA</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/clinica/create" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/addhospital.png') }}" alt="">
                    <p class="mt-2 text-center"> NUEVA CLINICA</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/consultation.png') }}" alt="">
                    <p class="mt-2 text-center">  CONSULTORIOS </p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/addconsultation.png') }}" alt="">
                    <p class="mt-2 text-center"> NUEVO CONSULTORIO </p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/usuarios" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/user.png') }}" alt="">
                    <p class="mt-2 text-center">  USUARIOS </p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/usuarios/create" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/adduser.png') }}" alt="">
                    <p class="mt-2 text-center">   NUEVO USUARIO </p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/citas" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/emptycalendar.png') }}" alt="">
                    <p class="mt-2 text-center">DÍAS SIN CITAS </p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
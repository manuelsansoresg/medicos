@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row vh-100 justify-content-center align-items-center"><!-- Agregado vh-100 y align-items-center -->
        <div class="col-md-8">
            <div class="card pt-5 px-5">
                <h5 class="text-center">BIENVENIDO AL SISTEMA: <b>{{ Auth::user()->name }}</b> </h5>
                <div class="row mt-3">
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/folder.png') }}" alt="">
                            <p>Actividades</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/timetable.png') }}" alt="">
                            <p>Citas</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/youth.png') }}" alt="">
                            <p>Pacientes</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/alarm.png') }}" alt="">
                            <p>Pendientes</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/dossier.png') }}" alt="">
                            <p>Expedientes</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/gear.png') }}" alt="">
                            <p>Administración</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="" class="link-secondary text-decoration-none fw-bold">
                            <img class="img-fluid" src="{{ asset('image/logout.png') }}" alt="">
                            <p>Salir</p>
                        </a>
                    </div>
                    <!-- Agrega más columnas aquí si es necesario -->
                    <div class="col-12 text-center mt-3">
                        <p>
                           <small>
                            Derechos Reservados © 2010. Política de privacidad - Términos de uso. <br>
                            Sitio hospedado en la Red Virtual Empresarial. Derechos Reservados 2011.
                           </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

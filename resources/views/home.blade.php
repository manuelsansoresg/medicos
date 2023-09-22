@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-6 col-md-3 text-center">
            <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/folder.png') }}" alt="">
                <p class="mt-2 text-center">ACTIVIDADES</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="/admin/citas" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/timetable.png') }}" alt="">
                <p class="mt-2 text-center">CITAS</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="/admin/pacientes" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/youth.png') }}" alt="">
                <p class="mt-2 text-center">PACIENTES</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="/admin/pendientes" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/alarm.png') }}" alt="">
                <p class="mt-2 text-center">PENDIENTES</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/dossier.png') }}" alt="">
                <p class="mt-2 text-center">EXPEDIENTES</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="/admin/administracion" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/gear.png') }}" alt="">
                <p class="mt-2 text-center">ADMINISTRACIÓN</p>
            </a>
        </div>
        <div class="col-6 col-md-3 text-center">
            <a href="" class="link-secondary text-decoration-none fw-bold">
                <img class="img-fluid" src="{{ asset('image/logout.png') }}" alt="">
                <p class="mt-2 text-center">SALIR</p>
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
@endsection

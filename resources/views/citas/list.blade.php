@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item " >CITAS</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="fw-bold">PANEL DE ADMINISTRACIÓN DE CITAS</p>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/calendar.png') }}" alt="">
                    <p class="mt-2 text-center">HOY</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/schedule.png') }}" alt="">
                    <p class="mt-2 text-center">AGENDAR</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/schedulecancel.png') }}" alt="">
                    <p class="mt-2 text-center">CANCELAR</p>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="/admin/actividades" class="link-secondary text-decoration-none fw-bold">
                    <img class="img-fluid" src="{{ asset('image/clock.png') }}" alt="">
                    <p class="mt-2 text-center">HISTORIAL</p>
                </a>
            </div>
        </div>
    </div>
@endsection
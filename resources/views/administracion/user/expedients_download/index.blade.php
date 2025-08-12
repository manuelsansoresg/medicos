@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">PERMISOS DE USUARIO</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="container bg-white py-2">
    <form id="frm-config-download-expedient">

        @include('administracion.user.expedients_download.content_config')
    </form>
</div>
@stop
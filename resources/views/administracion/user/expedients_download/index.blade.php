@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">CONFIGURACIÓN DE DESCARGA DE EXPEDIENTES</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="container bg-white py-2">
    <form id="frm-config-download-expedient">

        <div class="row mt-3">
            <div class="col-12 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="consulta1" value="1">
                    <label class="form-check-label" for="consulta1">
                        DESCARGAR CONSULTA
                    </label>
                </div>
    
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="estudios2" value="2">
                    <label class="form-check-label" for="estudios2">
                        DESCARGAR ESTUDIOS
                    </label>
                  </div>
    
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="ambos3" value="3">
                    <label class="form-check-label" for="ambos3">
                        DESCARGAR TODOS
                    </label>
                  </div>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 mt-3">
                <h5>CONSULTA</h5>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 mt-3">
                <h5>ESTUDIOS</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescargaEstudios" id="imagenes1" value="1">
                    <label class="form-check-label" for="imagenes1">
                        CON IMÁGENES
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescargaEstudios" id="sinImagenes2" value="0">
                    <label class="form-check-label" for="sinImagenes2">
                        SIN IMÁGENES
                    </label>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="{{ $userId }}">
    </form>
</div>
@stop
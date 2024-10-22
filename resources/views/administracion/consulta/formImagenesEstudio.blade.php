@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"><a href="/admin/actividades">ACTIVIDADES</a></li>
                        <li class="breadcrumb-item active"> <a href="/admin/citas/{{ $ConsultaAsignado }}/list">CITAS</a></li>
                        <li class="breadcrumb-item active"> <a href="/admin/consulta/{{ $userCitaId }}/{{ $ConsultaAsignado }}/registro">CONSULTA</a></li>
                        <li class="breadcrumb-item active">IMAGENES</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3">
       
    
        <div class="col-12 mt-3">
            <form method="post" id="frm-imagen-estudio">
           {{--  @if ($user_id == null)
            @else
            <form method="post" action="/admin/usuarios" id="upd-frm-users">
            @endif --}}
                <div class="col-12">
                    <p class="text-info">Los campos marcados con * son requeridos</p>
                </div>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNombre" class="form-label">*ARCHIVO O IMAGEN</label>
                            <input type="file" class="form-control" name="file" required>
                            @if ($image != null)
                            <div class="col-12 text-center mt-3">
                                <img class="square-img" src="{{ asset('/image/estudios/'.$image->image) }}" alt="">
                                <a href="#" onclick="deleteImagenEstudio({{ $image->id }})" class="btn btn-danger btn-block mt-3"><i
                                    class="fas fa-trash"></i></a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <input type="hidden" name="estudioId" id="estudioId" value="{{ $estudioId }}">
                    <input type="hidden" id="ConsultaAsignado" value="{{ $ConsultaAsignado }}">
                    <input type="hidden" id="userCitaId" value="{{ $userCitaId }}">
                   
                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">ACTIVACIÓN DE USUARIO</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3 card">
        <div class="card-body">
            <div class="col-12 mt-3">
                <form method="post" action="{{ route('users.activation', $user->id) }}" enctype="multipart/form-data" id="frm-activation">
                    @csrf
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    @hasrole(['administrador'])
                    
                        <div class="col-12 py-3 mt-3">
                            <p class="lead">DATOS DEL USUARIO</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NOMBRES</label>
                                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">APELLIDO PATERNO</label>
                                    <input type="text" class="form-control" value="{{ $user->vapellido }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">  APELLIDO MATERNO</label>
                                    <input type="text" class="form-control" value="{{ $user->segundo_apellido }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">CÉDULA PROFESIONAL</label>
                                    <input type="text" class="form-control" value="{{ $user->vcedula }}" readonly>
                                    <small><a href="https://cedulaprofesonal.sep-org.mx/" target="_blank">CONSULTAR CÉDULA</a></small>
                                </div>
                            </div>
                        </div>
                    @endrole

                    <div class="col-12 py-3 mt-3">
                        <p class="lead">DOCUMENTACIÓN</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ine_front" class="form-label">INE FRONTAL</label>
                                @if ($user->ine_front == null)
                                    <input type="file" class="form-control" name="ine_front" id="ine_front" accept="image/*" onchange="previewImage(this, 'front-preview')">
                                @endif

                                @if($user->ine_front != null)
                                    <div class="mt-2" id="front-preview">
                                        <img src="{{ asset('ine/' . $user->ine_front) }}" class="img-thumbnail" style="max-height: 200px;">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteImage('front')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ine_back" class="form-label">INE REVERSO</label>
                                @if ($user->ine_back == null)
                                    <input type="file" class="form-control" name="ine_back" id="ine_back" accept="image/*" onchange="previewImage(this, 'back-preview')">
                                @endif

                                @if($user->ine_back != null)
                                    <div class="mt-2" id="back-preview">
                                        <img src="{{ asset('ine/' . $user->ine_back) }}" class="img-thumbnail" style="max-height: 200px;">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteImage('back')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @hasrole(['administrador'])
                        <div class="col-12 py-3 mt-3">
                            <p class="lead">VALIDACIÓN</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">VALIDAR CÉDULA</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_cedula_valid" id="cedula_valid_yes" value="1" {{ $user->is_cedula_valid == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cedula_valid_yes">
                                            SI
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_cedula_valid" id="cedula_valid_no" value="0" {{ $user->is_cedula_valid == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cedula_valid_no">
                                            NO
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endrole

                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection 
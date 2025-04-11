@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE PACIENTES</a> </li>
                    <li class="breadcrumb-item">PACIENTE</li>
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
                <form method="post" action="/admin/pacientes" id="frm-paciente">
               {{--  @if ($user_id == null)
                @else
                <form method="post" action="/admin/usuarios" id="upd-frm-users">
                @endif --}}
                    <div class="col-12">
                        <p class="text-info">Los campos marcados con * son requeridos</p>
                    </div>
                    <div class="col-12 py-3 mt-3">
                        <p class="lead">DATOS GENERALES</p>
                    </div>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputImss" class="form-label">CURP</label>
                                <small>Consulta tu CURP aquí <a href="https://www.gob.mx/curp/" target="_blank">consulta</a></small>
                                <input type="text" class="form-control" name="data[curp]" id="paciente-curp" value="{{ $user != null ? $user->curp : null }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">*NOMBRE(S)</label>
                                <input type="text" class="form-control" name="data[name]" id="paciente-nombre" value="{{ $user != null ? $user->name : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputApellido" class="form-label">*APELLIDO MATERNO</label>
                                <input type="text" class="form-control" name="data[vapellido]" id="paciente-apellido" value="{{ $user != null ? $user->vapellido : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputApellido" class="form-label">*APELLIDO PATERNO</label>
                                <input type="text" class="form-control" name="data[segundo_apellido]" id="paciente-segundo-apellido" value="{{ $user != null ? $user->segundo_apellido : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputSexo" class="form-label">SEXO</label>
                                @php
                                    $sexo = config('enums.sexo');
                                @endphp
                                <select name="data[sexo]"  id="sexo" class="form-control">
                                    @foreach ($sexo as $sexo)
                                        <option value="{{ $sexo }}">{{ $sexo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">FECHA NACIMIENTO</label>
                                <input type="date" class="form-control" name="data[fecha_nacimiento]" id="fecha_nacimiento" value="{{ $user != null ? $user->fecha_nacimiento : null }}">
                            </div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">TELEFONO</label>
                                <input type="text" class="form-control" name="data[ttelefono]" id="telefono" value="{{ $user != null ? $user->ttelefono : null }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">DIRECCIÓN</label>
                                <textarea name="data[tdireccion]" id="direccion" cols="30" rows="5" class="form-control">{{ $user != null ? $user->tdireccion : null }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="num_seguro" class="form-label">N° I.M.S.S</label>
                                <input type="text" class="form-control" name="data[num_seguro]" id="num_seguro" value="{{ $user != null ? $user->num_seguro : null }}">
                            </div>
                        </div>
                        
                       
                        
                        <div class="col-md-6" id="content-status">
                            <div class="mb-3">
                                <label for="status" class="form-label">*ACTIVO</label>
                                <select name="data[status]" id="status" class="form-control" required>
                                    
                                   @foreach (config('enums.status') as $key => $item)
                                       <option value="{{ $key }}" {{ $user != null && $user->status == $key ? 'selected' : null  }}>{{ $item }}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        @hasrole('administrador')
                        <div class="col-md-6" id="usuario_propietario">
                            <div class="form-group">
                                <label for="InputDoctor">USUARIO PROPIETARIO</label>
                                <select name="data[usuario_principal]" id="usuario_principal" class="form-control select2multiple">
                                    @foreach ($userAdmins as $userAdmin)
                                        <option value="{{ $userAdmin->id }}" {{ $user != null && $userAdmin->id == $user->usuario_principal ? 'selected' : null }}>{{ $userAdmin->name }}</option>
                                    @endforeach
                                </select>
                                <small id="doctorHelp" class="form-text text-muted">Usuario propietario para aplicar filtros de busqueda.</small>
                            </div>
                        </div>
                        @endrole
                        <div id="content-data-system">

                            <div class="col-12 py-3 mt-3">
                                <p class="lead">DATOS PARA INGRESAR AL SISTEMA</p>
                                <p class="text-info">Solo llenar cuando se quiera dar acceso al sistema</p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputCorreo" class="form-label">CORREO</label>
                                    <input type="email" class="form-control" name="data[email]" id="inputCorreo" value="{{ $user != null ? $user->email : null }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">CONTRASEÑA</label>
                                    <input type="password" class="form-control" name="password" id="inputPassword">
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}" >
                                <button class="btn btn-primary" id="btn-guardar-paciente">Guardar</button>
                                <button class="btn btn-primary" id="btn-vincular-paciente" style="display: none;" onclick="vincularPaciente()">Vincular paciente</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    
    </div>
</div>


@endsection
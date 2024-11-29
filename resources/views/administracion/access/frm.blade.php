@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/acceso">ACCESO</a> </li>
                    <li class="breadcrumb-item">ACCESO</li>
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
            <form method="post" action="/admin/acceso" id="frm-access">
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
                    @php
                        $status = config('enums.status');
                    @endphp
                    @hasrole('administrador')
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">* USUARIO</label>
                                <select name="data[user_id]" id="user_id" class="form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $access != null && $access->user_id == $user->id ? 'selected' : null }}> {{ $user->name }} </option>
                                    @endforeach
                                </select>
                                <div id="error-data_user_id" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label">* # USUARIOS DOCTORES</label>
                            <input type="number" class="form-control" name="data[num_doctor]" id="num_doctor" value="{{ $access != null ? $access->num_doctor : null }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label"># USUARIOS AUXILIARES</label>
                            <input type="number" class="form-control" name="data[num_auxiliar]" id="num_auxiliar" value="{{ $access != null ? $access->num_auxiliar : null }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label">*FECHA VENCIMIENTO</label>
                            <input type="date" class="form-control" name="data[fecha_vencimiento]" id="fecha_vencimiento" value="{{ $access != null ? $access->fecha_vencimiento : null }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label">COSTO</label>
                            <input type="text" class="form-control" name="data[costo]" id="costo" value="{{ $access != null ? $access->costo : null }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label">*PAGADO</label>
                            <select name="data[is_pagado]" id="is_pagado" class="form-control" required>
                                @foreach ($status as $key =>  $pay)
                                    <option value="{{ $key }}" {{ $access != null && $key == $access->is_pagado ? 'selected' : null }}>{{ $pay }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCedula" class="form-label">*ACTIVO</label>
                            <select name="data[status]" id="status" class="form-control" required>
                                @foreach ($status as $key =>  $active)
                                    <option value="{{ $key }}" {{ $access != null && $key == $access->status ? 'selected' : null }}>{{ $active }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                    @endhasrole
                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            <input type="hidden" id="acces_id" name="acces_id" value="{{ $acces_id }}" >
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>


@endsection
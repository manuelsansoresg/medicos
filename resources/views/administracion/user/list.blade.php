@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE USUARIOS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop
@inject('Muser', 'App\Models\User')
@inject('Maccess', 'App\Models\Access')
@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3 card">
            <div class="card-body">

                <div class="col-12 text-end">
                    <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                    @hasrole(['administrador'])
                        <a href="/admin/usuarios/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                        @else
                        @if (isset($getUsedStatusPackages['totalUsuariosSistema']['isLimit']) && $getUsedStatusPackages['totalUsuariosSistema']['isLimit']  == false)
                            <a href="/admin/usuarios/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                        @endif
                    @endrole
                    
                    
                    
                </div>
                <div class="col-12 mt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>CORREO</th>
                                <th>CONSULTORIO ASIGNADO</th>
                                <th>ACTIVAR USUARIO</th>
                                <th>ESTATUS ACTIVACIÓN</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td> {{ $user->email }} </td>
                                    <td>
                                        @php
                                            $isConsultorio = $Muser::isConsultAssign($user->id);
                                        @endphp
                                        @if ($isConsultorio == true)
                                            <span class="text-primary">SÍ</span>
                                        @else
                                            <span class="text-warning">NO</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $vinculacion = $Muser::getVinculacionUser($user->id);
                                            
                                        @endphp
                                        @if ($vinculacion && $vinculacion->isValidateCedula)
                                            <span class="text-primary">SÍ</span>
                                        @else
                                            <span class="text-warning">NO</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($vinculacion && $vinculacion->isValidateCedula)
                                        <span class="{{ $vinculacion->is_cedula_valid == 1 ? 'text-primary' : 'text-warning' }}">{{ $user->is_cedula_valid == 1 ? 'SÍ' : 'NO' }}</span>
                                        @else
                                            <span class="text-success">N/A</span>
                                        @endif
                                    </td>
                                    <td class="col-3">
                                        {{-- solo puede editar admin, medico, auxiliar --}}
                                        @php
                                            $myUser = Auth::user();
                                            $getRoleName = $myUser->getRoleNames()[0];
                                            $access = $Maccess::getMyAccess();
                                            $limitUser = $Muser::limitAllUsers($access, $user->id);
                                        @endphp
                                        @if ($getRoleName == 'administrador'|| $getRoleName == 'medico' || $getRoleName == 'auxiliar')
                                        @if ($vinculacion && $vinculacion->isValidateCedula)
                                            <a href="/admin/usuarios/activar/{{ $user->id }}" class="btn btn-success"><i class="fas fa-check"></i></a>
                                       
                                        @endif
                                        <a href="/admin/consulta-asignado/{{ $user->id }}" class="btn btn-warning text-white"><i class="fas fa-building"></i></a>
                                           @if ($limitUser == false || $getRoleName == 'administrador')
                                            <a href="/admin/usuarios/{{ $user->id }}/edit" class="btn btn-primary"><i
                                                    class="fas fa-edit"></i></a>
                                           @endif
    
                                        @endif
                                        <a href="/admin/configuracion-descargas/{{ $user->id }}" class="btn btn-success"><i class="fas fa-users-cog"></i></a>
                                        <a href="#" onclick="deleteUser({{ $user->id }})" class="btn btn-danger"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

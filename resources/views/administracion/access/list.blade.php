@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTADO</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop
@inject('Maccess', 'App\Models\Access')
@inject('Muser', 'App\Models\User')

@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="/admin/acceso/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>USUARIO</th>
                                <th># DOCTORES</th>
                                <th># AUXILIARES</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>COSTO</th>
                                <th>PAGADO</th>
                                <th>ACTIVO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $query)
                                <tr>
                                    <td>{{ $query->name }}</td>
                                    <td> 
                                        @php
                                            $userCount     = $Muser::countUsersCreate($query->user_id);
                                            $usersDoctor   = $userCount['medico'];
                                            $usersAuxiliar = $userCount['auxiliar'];
                                            $colorDoctor   = $Muser::colorUsersCreate($usersDoctor, $query->num_doctor);
                                            $colorAuxiliar   = $Muser::colorUsersCreate($usersAuxiliar, $query->num_doctor);
                                        @endphp
                                        <span class="badge {{ $colorDoctor }}"> {{ $usersDoctor }} </span> - <span class="badge bg-success"> {{ $query->num_doctor }} </span>
                                    </td>
                                    <td> 
                                        <span class="badge {{ $colorAuxiliar }}"> {{ $usersAuxiliar }} </span> -  <span class="badge bg-success"> {{ $query->num_auxiliar }} </span>
                                    </td>
                                    <td> {{ $query->fecha_vencimiento }}</td>
                                    <td> {{ $query->costo }}</td>
                                    <td> 
                                        @if ($query->is_pagado)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-success">No</span>
                                        @endif
                                    </td>
                                    <td> 
                                        @if ($query->status)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-success">No</span>
                                        @endif
                                    </td>
                                    <td class="col-3">
                                        <a href="/admin/acceso/{{ $query->id }}" class="btn btn-warning text-white"><i
                                            class="fas fa-eye"></i></a>
                                        <a href="/admin/acceso/{{ $query->id }}/edit" class="btn btn-primary"><i
                                                class="fas fa-edit"></i></a>
                                        <a href="#" onclick="deleteAccess({{ $query->id }})" class="btn btn-danger"><i
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

@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="/admin/acceso">ACCESO</a></li>
                        <li class="breadcrumb-item">LISTADO</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3">
           
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>USUARIO</th>
                                <th>TIPO</th>
                                <th>ACTIVO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        @php
                                             $getRoleName = $user->getRoleNames();
                                        @endphp
                                        {{ ucfirst($getRoleName[0]) }}
                                    </td>
                                    <td>
                                        @if ($user->status == 1)
                                        <span class="badge bg-success">Sí</span>
                                            @else
                                            <span class="badge bg-danger">NO</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE PENDIENTES</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="/admin/pendientes/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>PENDIENTE</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>ACTIVO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            <tr>
                                <td>{{ $query->pendiente }}</td>
                                <td> {{ $query->fecha }} </td>
                                <td> {{ $query->hora }} </td>
                                <td> {{ $query->status == 1 ? 'SÍ' : 'NO' }} </td>
                                <td class="col-3">
                                    <a href="/admin/pendientes/{{ $query->id }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deletePendiente({{ $query->id }})" class="btn btn-danger"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

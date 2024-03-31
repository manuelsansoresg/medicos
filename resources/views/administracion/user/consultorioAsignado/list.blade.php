@extends('adminlte::page')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"> <a href="/admin/usuarios"> LISTA DE USUARIOS </a></li>
                        <li class="breadcrumb-item active">ASIGNAR EL CONSULTORIO</li>
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
                <a href="/admin/consulta-asignado/{{ $id }}/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>HORA INICIAL - HORA FINAL</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            @if ($query->ihorainicial != 0 && $query->ihorafinal != '')
                            <tr>
                                <td>{{ $query->name }}</td>
                                <td> 
                                    {{ $query->ihorainicial }} - {{ $query->ihorafinal }}    
                                </td>
                                <td class="col-2">
                                    
                                    <a href="/admin/consulta-asignado/{{ $query->id }}" class="btn btn-warning text-white"><i class="fas fa-building"></i></a>
                                    <a href="/admin/usuarios/{{ $query->id }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteUser({{ $query->id }})" class="btn btn-danger"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

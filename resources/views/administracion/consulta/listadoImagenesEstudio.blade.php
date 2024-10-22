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
            <div class="col-12 text-end">
                <a href="/admin/estudio-imagenes/{{ $estudioId }}/{{ $userCitaId }}/{{ $ConsultaAsignado }}/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>IMAGEN</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            <tr>
                                <td>
                                    <img class="square-img" src="{{ asset('/image/estudios/'.$query->image) }}" alt="">

                                </td>
                                <td class="col-3">
                                    <a href="/admin/estudio-imagenes/{{ $query->id }}/{{ $estudioId }}/{{ $userCitaId }}/{{ $ConsultaAsignado }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteImagenEstudio({{ $query->id }})" class="btn btn-danger"><i
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
@extends('layouts.template')
@section('content_header')
    <div class="container">
        <div class="row mt-3">

            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">SIN CITAS</li>
                        {{--  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                     <li class="breadcrumb-item ">CITAS</li> --}}
                    </ol>
                </nav>
            </div>



        </div>
    </div>
@stop


@section('content')


    <div class="container bg-white py-2">
        @if ($isChangeConsultorio == false)
            @if ($isEmptyConsultorio)
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                        <a href="/admin/sin_citas/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    </div>


                    <div class="col-12">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Fecha inicial</th>
                                    <th>Fecha final</th>
                                    <th>Motivo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($query as $query)
                                    <tr>
                                        <td> {{ $query->dfecha }} </td>
                                        <td> {{ $query->dfechafin }} </td>
                                        <td>{{ $query->tmotivo }} </td>
                                        <td>
                                            <a href="/admin/sin_citas/{{ $query->idfechaespeciales }}/edit"
                                                class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                            <a href="#" onclick="deleteSinCitas({{ $query->idfechaespeciales }})"
                                                class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
            <div class="col-12">
                {{-- <div class="alert alert-danger" role="alert">
                    No se encuentron consultorios relacionados con su cuenta.
                <br>
                Favor de revisar si tiene asignado un consultorio en el apartado de Usuarios del menú izquierdo
                </div> --}}
            </div>
            @endif
        @else
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    Favor de elegir una clinica y un consultorio en la parte superior y seleccionar aplicar filtro
                </div>
            </div>
        @endif

    </div>
@endsection

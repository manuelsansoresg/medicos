@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE CATALOGOS</li>
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

            <div class="col-12 text-end">
                <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
               
               {{--  @if ($getUsedStatusPackages['totalConsultorioExtra']['isLimit']  == false)
                    <a href="/admin/consultorio/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @endif --}}
                <a href="/admin/catalogo/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>PORCENTAJE GANANCIA</th>
                            <th>DESCRIPCIÓN</th>
                            <th>ACTIVO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            <tr>
                                <td> {{ $query->nombre }} </td>
                                <td> {{ format_price($query->precio) }} </td>
                                <td> {{ format_price($query->porcentaje_ganancia) }} </td>
                                <td> {{ $query->descripcion }} </td>
                                <td>
                                    @if ($query->status == 1)
                                        SÍ
                                    @endif 
                                    @if ($query->status == 0)
                                        NO
                                    @endif 
                                </td>
                                
                                <td class="col-2">
                                    <a href="/admin/catalogo/{{ $query->id }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteCatalogo({{ $query->id }})" class="btn btn-danger"><i
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

@extends('adminlte::page')
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
   
    <div class="row mt-3">
        <div class="col-12 text-right">
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
                                <a href="/admin/sin_citas/{{ $query->idfechaespeciales }}/edit" class="btn btn-primary"><i
                                    class="fas fa-edit"></i></a>
                            <a href="#" onclick="deleteSinCitas({{ $query->idfechaespeciales }})" class="btn btn-danger"><i
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

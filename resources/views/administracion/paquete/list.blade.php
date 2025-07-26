@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE PAQUETES</li>
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
                <a href="/admin/paquete/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <div class="col-12">
                
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>ACTIVO</th>
                            <th>ELEMENTOS</th>
                            <th>TIPO</th>
                            <th>VALIDAR CÉDULA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            <tr>
                                <td> {{ $query->nombre }} </td>
                                <td> {{ format_price($query->precio) }} </td>
                                <td> 
                                    @if ($query->status == 1)
                                        SÍ
                                    @endif 
                                    @if ($query->status == 0)
                                        NO
                                    @endif 
                                </td>
                               
                                <td> 
                                    
                                    @if ($query->items->isEmpty())
                                        <span class="text-muted">Sin elementos</span>
                                    @else
                                        @foreach ($query->items as $item)
                                            <span class="badge bg-info">{{ $item->catalogPrice->nombre ?? 'Sin nombre' }} ({{ $item->max }}) </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($query->owner_type == 'clinica')
                                        CLÍNICA
                                    @endif
                                    @if ($query->owner_type == 'consultorio')
                                        CONSULTORIO
                                    @endif
                                </td>
                                <td>
                                    @if ($query->isValidateCedula == 1)
                                        SÍ
                                    @endif 
                                    @if ($query->isValidateCedula == 0)
                                        NO
                                    @endif 
                                </td>
                                <td class="col-2">
                                    <a href="/admin/paquete/{{ $query->id }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deletePaquete({{ $query->id }})" class="btn btn-danger"><i
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

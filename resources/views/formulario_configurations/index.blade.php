@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">PLANTILLA CONSULTA</li>
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
            <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
            <a href="{{ route('template-formulario.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
        </div>
        <div class="col-12 mt-3">
            <div class="alert alert-primary" role="alert">
                La plantilla es un formulario personalizado que saldrá al momento de realizar una consulta, recuerda activar la plantilla que usaras para la consulta
              </div>
        </div>
        <div class="col-12">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>NOMBRE DE LA PLANTILLA</th>
                        <th>ESTATUS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($configurations as $configuration)
                    <tr>
                        <td>
                            {{ $configuration->name }}
                           
                        </td>
                        <td>
                            @if ($configuration->active == 1)
                                SÍ
                            @else
                                NO
                            @endif
                           
                        </td>
                        <td>
                            <a onclick="activarPlantilla({{ $configuration->id }})" class="btn btn-success"><i class="far fa-check-circle"></i></a>
                            
                                <a href="{{ route('template-formulario.edit', $configuration->id) }}" class="btn btn-primary"><i
                                class="fas fa-edit"></i></a>
                                <a href="#" onclick="deletePlantilla({{ $configuration->id }})" class="btn btn-danger"><i
                                    class="fas fa-trash"></i></a>
                            {{-- <form action="{{ route('template-formulario.destroy', $configuration->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i
                                    class="fas fa-trash"></i></button>
                            </form> --}}
                        </td>
                    </tr>
                       
                    
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
    
@endsection
@extends('adminlte::page')
@section('content_header')
    <div class="container">
        <div class="row mt-3">
           
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"><a href="/admin/actividades">ACTIVIDADES</a></li>
                        <li class="breadcrumb-item active">CITAS</li>
                    </ol>
                </nav>
            </div>

            
            
        </div>
    </div>
@stop

@section('content')
<div class="container">
    <div class="row mt-3">
       
      
        <div class="col-12">
            <div class="card py-2 px-2">
                <div class="responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>HORA</th>
                                <th>PACIENTE</th>
                                <th>MOTIVO CONSULTA</th>
                                <th>ESTATUS</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($horarios as $horario)
                                <tr>
                                    <td> {{ $horario['hora']  }} </td>
                                    <td>
                                        {{ $horario['paciente'] }}
                                    </td>
                                    <td>
                                        {{ $horario['motivo'] }}
                                    </td>
                                    <td>
                                        {{ $horario['isDisponible'] }}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
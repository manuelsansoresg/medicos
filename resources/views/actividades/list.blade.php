@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">ACTIVIDADES</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop
@inject('UserCita', 'App\Models\UserCita')
@section('content')
    <div class="container">
        <div class="row mt-3">
            @if ($isChangeConsultorio == false)
                @if ($isEmptyConsultorio)
                    <div class="col-12">
                        <div class="card py-2 px-2">
                            <h5> ACTIVIDADES PARA EL DÍA DE HOY</h5>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                        aria-selected="true"> {{ count($sqlpend) }}
                                        PENDIENTES PARA HOY</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">ACTIVIDAD EN (LOS)
                                        CONSULTORIOS</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                   <div class="row mt-3">
                                        <div class="col-12 col-md-8">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Pendiente</th>
                                                        <th>Hora</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($sqlpend as $respend)
                                                    <tr>
                                                        <td>
                                                            {{ $respend->pendiente }}
                                                        </td>
                                                        <td>{{ $respend->hora }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                   </div>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <table class="table mt-3">
                                        <thead>
                                            <tr>
                                                <th>TURNO</th>
                                                <th>RANGO HORARIO</th>
                                                <th># CITAS</th>
                                                <th>CONSULTORIO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($consultas != null)
                                                @foreach ($consultas as $consulta)
                                                    <tr>
                                                        <td>
                                                            {{ config('enums.turno')[$consulta->iturno] }}
                                                        </td>
                                                        <td>
                                                            DE {{ $consulta->ihorainicial }}:00 HRS. A
                                                            {{ $consulta->ihorafinal }}:00 HRS.<br>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $getcitas = $UserCita::where(
                                                                    'consulta_asignado_id',
                                                                    $consulta->idconsultasignado,
                                                                );
                                                                $totalCitas = $getcitas->count();
                                                                $cita = $getcitas->first();
                                                            @endphp
                                                            {{ $totalCitas }}
                                                        </td>
                                                        <td>
                                                            {{ $consulta->vnumconsultorio }}
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary"
                                                                href="/admin/citas/{{ $consulta->idconsultasignado }}/list">
                                                                <strong>INICIAR CONSULTA</strong>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            No se encuentron consultorios relacionados con su cuenta.
                            <br>
                            Favor de revisar si tiene asignado un consultorio en el apartado de Usuarios del menú izquierdo
                        </div>
                    </div>
                @endif
            @else
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        Favor de elegir el consultorio para poder filtrar resultados en el siguiente enlace
                        <br>
                        <a href="/query/viewClinicaYConsultorio">Elegir consultorio</a>
                    </div>
                </div>
            @endif


        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item " >ACTIVIDADES</li>
                </ol>
              </nav>
        </div>
        <div class="col-12 text-center fw-bold">
            ACTIVIDADES PARA EL DÍA DE HOY
        </div>
        <div class="col-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true"> {{ count($sqlpend) }}
                        PENDIENTES PARA HOY</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">ACTIVIDAD EN (LOS)
                        CONSULTORIOS</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="mt-3">
                        @foreach ($sqlpend as $respend)
                            <li>
                                {{ $respend->tpendiente }}
                                <p>
                                    {{ $respend->vhora }}
                                </p>
                            </li>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
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
                            @foreach ($consultas as $consulta)
                                <tr>
                                    <td> 
                                        {{ config('enums.turno')[$consulta->iturno] }} 
                                    </td>
                                    <td>
                                        DE {{ $consulta->ihorafinal }}:00 HRS.  A  {{ $consulta->ihorafinal }}:00 HRS.<br>
                                    </td>
                                    <td>0</td>
                                    <td>
                                        {{ $consulta->vnumconsultorio }}
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="procesos/usrvisto.php?m=<? echo $idldoctores;?>&d=<? echo $v?>&c=<? echo $idconsultorio?>&h=<? echo $contador?>&cd=1" >
                                            <strong>INICIAR CONSULTA</strong>
                                            </a>
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

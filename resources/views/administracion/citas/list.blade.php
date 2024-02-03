@extends('adminlte::page')
@section('content_header')
    <div class="container">
        <div class="row mt-3">
           
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE CITAS</li>
                       {{--  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                     <li class="breadcrumb-item ">CITAS</li> --}}
                    </ol>
                </nav>
            </div>

            
            
        </div>
    </div>
@stop

@inject('MconsultaAsignado', 'App\Models\ConsultaAsignado')

@section('content')


<div class="container bg-white py-2">
    <div class="row mt-3 justify-content-center">
        <div class="col-6">
            <form action="" method="GET" id="frm-fecha">
                <div class="form-group">
                    <label for="InputFecha">FECHA CITA</label>
                    <input type="date" class="form-control" id="InputFecha" name="fecha"  placeholder="Enter email" value="{{ $fecha  }}">
                    <small id="emailHelp" class="form-text text-muted">Elige una fecha para ver las citas.</small>
                  </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 text-right">
            <a href="/admin/citas/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
        </div>

        <div class="col-12">
            <div class="row">
               {{--  <div class="form-group">
                    <label for="selectPaciente">SELECCIONA PACIENTE</label>
                    <select name="data[paciente]" id="selectPaciente" class="form-control col-12">
                        
                     </select>
                    <small id="emailHelp" class="form-text text-muted"> <a href="">Cita primera vez</a> </small>
                  </div> --}}
                  
                @if ($fechasEspeciales != null)
                     
                @foreach ($consultaAsignados as $resultado)
                <div class="col-12 col-md-4">
                    Consultorio: {{ $resultado['consultorio'] }}
                    @foreach ($resultado['horarios'] as $key =>  $item)
                        <div class="row mt-2">
                            <div class="col-6">
                                <p>Hora: {{ $item['hora'] }}</p>
                            </div>
                            <div class="col-6">
                                <p>Status:
                                    @if ($item['statusconactivanop'] == 0)
                                        <a onclick="setParamAddCita({{  $resultado['id'] }}, '{{ $item['horaSinFormato'] }}')"  href="#">LIBRE {{  $resultado['id'] }} </a>
                                    @else
                                        <a href="#">OCUPADO</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            
                @else
                    <h4>Lo sentimos el día de hoy el medico seleccionado no tiene consultas</h4>
                @endif
            </div>
        </div>
    </div>
</div>
@include('administracion.citas.modalCita')
@endsection

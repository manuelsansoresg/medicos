@extends('layouts.template')
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
@inject('Mconsultorio', 'App\Models\Consultorio')

@section('content')


<div class="container bg-white py-2">
    <div class="row mt-3 justify-content-center">
        @if ($isChangeConsultorio == false)
            @if ($isEmptyConsultorio)
            <div class="col-12 col-md-8">
                <form action="" method="GET" id="frm-cita">
                    @hasrole('administrador')
                    <div class="form-group">
                        <label for="InputDoctor">DOCTOR</label>
                        <select name="data[iddoctor]" id="iddoctor" class="form-control select2multiple">
                            @foreach ($userAdmins as $userAdmin)
                                <option value="{{ $userAdmin->id }}">{{ $userAdmin->name }}</option>
                            @endforeach
                        </select>
                        <small id="doctorHelp" class="form-text text-muted">Doctor que se le asignara la consulta.</small>
                    </div> 
                    @else
                    <input type="hidden" name="data[iddoctor]" id="iddoctor" value="{{ $iddoctor }}">
                    @endrole
                    @php
                        $consultorio = Session::get('consultorio');
                        
                    @endphp
                   
                    @if ($consultorio == 0)
                        <select name="data[id_consultorio]" id="" class="form-control select2multiple">
                            @foreach ($getAsignedConsultories as $getAsignedConsultory)
                               
                                    <option value="{{  $getAsignedConsultory->idconsultorios }}">{{ $getAsignedConsultory->vnumconsultorio }}</option>
                               
                            @endforeach
                        </select>
                    @endif
                    <div class="form-group">
                        <label for="InputFecha">FECHA CITA</label>
                        <input type="date" class="form-control" id="InputFecha" name="data[fecha]" onchange="setCita()"  placeholder="Enter email" value="{{ $fecha  }}">
                        <small id="fechaHelp" class="form-text text-muted">Elige una fecha para ver las citas.</small>
                    </div>
                    <div class="form-group">
                        <label for="InputFecha">SELECCIONA PACIENTE:</label>
                        
                        <div id="content-paciente-add" style="display: none">
                            <span id="paciente-add"></span> <a href="#" onclick="changePacienteCita()" class="btn btn-primary">Actualizar</a>
                        </div>
                        <input type="search" id="busqueda-pacientes" name="search" class="form-control" placeholder="Buscar paciente">
                        
                    </div>
                    <input type="hidden" name="data[hora]" id="hora">
                    <input type="hidden" name="data[consulta_asignado_id]" id="consulta_asignado_id">
                    <input type="hidden" name="data[paciente_id]" id="paciente_id">
                    <div id="content-form" style="display: block">
                        <div class="form-group">
                            <label for="InputFecha">HORA SELECCIONADA</label>
                            <small id="horaSeleccionada" class="form-text text-muted"></small>
                        </div>
                        <div id="content-hoursCita">
                        </div>
                       
                       
                        <div class="form-group">
                            <label for="InputFecha">MOTIVO  DE CONSULTA:</label>
                            <textarea name="data[motivo]" cols="30" rows="4" class="form-control"></textarea>
                            
                        </div>
                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <button class="btn btn-primary" id="btn-add-office-user">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="col-12">
               {{--  <div class="alert alert-danger" role="alert">
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

   
</div>
@include('administracion.citas.modalCita')
@endsection

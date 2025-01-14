@extends('layouts.template')
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
                                        @if ($horario['isDisponible'] == 1)
                                            Disponible
                                            @else
                                            Ocupado
                                        @endif
                                    </td>
                                    <td>
                                        @if ($horario['userCitaId'] != null)
                                            <a href="/admin/consulta/{{ $horario['userCitaId'] }}/{{ $consultaAsignadoId }}/registro" class="btn btn-primary"><i class="fas fa-people-arrows"></i></a>
                                            <a onclick="liberarCita({{ $horario['userCitaId'] }})" class="btn btn-danger pointer"><i class="fas fa-user-times"></i></a>
                                            @else
                                            <a class="pointer btn btn-primary" onclick="addUserCita({{ $consultaAsignadoId }}, '{{  $horario['hora'] }}')"><i class="fas fa-user-plus"></i></a>
                                        @endif
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

{{-- modal --}}
<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserLabel">Agregar paciente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="GET" id="frm-cita">
            <div class="modal-body">
               <div class="container">
                <div class="row">
                    <div class="col-12">
                        @hasrole('administrador')
                        <div class="form-group">
                            <div class="col-12">
                                <label for="InputDoctor">DOCTOR</label>
                            </div>
                            <div class="col-12">
                                <select name="data[iddoctor]" id="iddoctor" class="form-control select2multiple">
                                    @foreach ($userAdmins as $userAdmin)
                                        <option value="{{ $userAdmin->id }}">{{ $userAdmin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="doctorHelp" class="form-text text-muted">Doctor que se le asignara la consulta.</small>
                        </div> 
                        @else
                        <input type="hidden" name="data[iddoctor]" id="iddoctor" value="{{ $iddoctor }}">
                        @endrole
                        <div class="form-group col-12">
                            <label for="InputFecha">SELECCIONA PACIENTE:</label>
                            
                            <div id="content-paciente-add" style="display: none">
                                <span id="paciente-add"></span> <a href="#" onclick="changePacienteCita()" class="btn btn-primary">Actualizar</a>
                            </div>
                            <input type="search" id="busqueda-pacientes" name="search" class="form-control" placeholder="Buscar crédito">
                            
                        </div>
                        <div class="form-group">
                            <label for="InputFecha">MOTIVO  DE CONSULTA:</label>
                            <textarea name="data[motivo]" cols="30" rows="4" class="form-control"></textarea>
                            
                        </div>
        
                        <input type="hidden" name="data[hora]" id="hora">
                        <input type="hidden" name="data[consulta_asignado_id]" id="consulta_asignado_id">
                        <input type="hidden" name="data[paciente_id]" id="paciente_id">
                        <input type="hidden" name="data[fecha]" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>

      </div>
    </div>
  </div>
@stop
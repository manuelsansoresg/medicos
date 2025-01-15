
@extends('layouts.template')
@inject('MComments', 'App\Models\Comment')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                            <li class="breadcrumb-item"> <a href="/admin/solicitudes/{{ $solicitud->id }}"> ETAPAS </a> </li>

                            <li class="breadcrumb-item"> Validar información de la solicitud </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12">
                <table class="table table-borderless">
                    @hasrole(['administrador'])
                            @if ($solicitud->catalog_prices_id == 1)
                                <tr>
                                    <td colspan="2">
                                        <p class="h6 color-secondary">DATOS DEL COMPRADOR</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>NOMBRE(S)</td>
                                    <td>{{ $solicitud->name }} </td>
                                </tr>
                                <tr>
                                    <td>PRIMER APELLIDO</td>
                                    <td>{{ $solicitud->vapellido }}</td>
                                </tr>
                                <tr>
                                    <td>SEGUNDO APELLIDO</td>
                                    <td>{{ $solicitud->segundo_apellido }}</td>
                                </tr>
                                <tr>
                                    <td>CLINICA</td>
                                    <td>{{ $solicitud->clinica }}</td>
                                </tr>
                                <tr>
                                    <td>DIRECCIÓN</td>
                                    <td>{{ $solicitud->tdireccion }}</td>
                                </tr>
                                <tr>
                                    <td>CÉDULA PROFESIONAL</td>
                                    <td>{{ $solicitud->vcedula }} 
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"> <a href="https://www.cedulaprofesional.sep.gob.mx/cedula/presidencia/indexAvanzada.action" target="_blank">CONSULTAR CÉDULA</a></td>
                                </tr>

                                <tr>
                                    <td>CÉDULA PROFESIONAL VÁLIDA</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_cedula_valid" id="is_cedula_valid1" value="1" {{ $solicitud->is_cedula_valid == 1 ? 'checked' : null}}>
                                            <label class="form-check-label" for="is_cedula_valid1">
                                              SÍ 
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_cedula_valid" id="is_cedula_valid2" value="0" {{ $solicitud->is_cedula_valid == 0 || $solicitud->is_cedula_valid == null ? 'checked' : null}}>
                                            <label class="form-check-label" for="is_cedula_valid2">
                                              NO
                                            </label>
                                          </div>
                                       
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2" class="text-end"> 
                                        <a href="/admin/solicitudes/{{ $solicitud->id }}" class="btn btn-success">Volver</a>
                                        <a onclick="validarCedula({{ $solicitud->user_id }}, {{ $solicitud->id }})" class="btn btn-primary">Guardar</a>
                                    </td>
                                </tr>
                           
                            @endif
                        @endrole
                </table>
            </div>
        </div>
    </div>
@endsection
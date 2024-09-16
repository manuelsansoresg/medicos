@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">CONFIGURACIÓN DE DESCARGA DE EXPEDIENTES</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop

@section('content')
@inject('MmyFieldConfigDownload', 'App\Models\FieldConfigDownload') 
<div class="container bg-white py-2">
    <form id="frm-config-download-expedient">

        <div class="row mt-3">
            <div class="col-12 mt-3">
                <div class="form-check">
                    @php
                        $permissionDownload     = $user->hasPermissionTo('Descargar consulta') == 1 ? 1: 0 ;
                        $permissionAny     = $user->hasPermissionTo('Descargar ninguno') == 1 ? 1: 0 ;
                        $permisionDownloadAll   = $user->hasPermissionTo('Descargar todos')    == 1 ? 1: 0 ;
                        $permisionDownloadStudy = $user->hasPermissionTo('Descargar estudios con imagenes')    == 1 ? 1 : 0 ;
                    @endphp
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="consulta1" value="1"
                    @if ($permissionDownload == 1)
                        {{ 'checked'}}
                    @endif
                    >
                    <label class="form-check-label" for="consulta1">
                        DESCARGAR CONSULTA
                    </label>
                </div>
    
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="estudios2" value="2"
                    @if ($permissionDownload == 0)
                    {{ 'checked'}}
                    @endif
                    >
                    <label class="form-check-label" for="estudios2">
                        DESCARGAR ESTUDIOS
                    </label>
                  </div>
    
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="ambos3" value="3" {{ $permisionDownloadAll == 1 ? 'checked' : null}}>
                    <label class="form-check-label" for="ambos3">
                        DESCARGAR TODOS 
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescarga" id="ambos4" value="1" {{ $permissionAny == 1 ? 'checked' : null}}>
                    <label class="form-check-label" for="ambos3">
                        DESCARGAR NINGUNO
                    </label>
                  </div>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 mt-3">
                <h5>CONSULTA</h5>
                @hasrole('administrador')

                @endhasrole
                
                @hasrole('medico|administrador')
                @if ($configurations != null)
                @php
                    $myConfiguration = $configurations;
                    $fields = $myConfiguration->fields;
                @endphp
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>CAMPO</th>
                            <th>PERMITIR DESCARGA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fields as $field)
                            @php
                                $getConfigField = $MmyFieldConfigDownload::where(['user_id' => $myConfiguration->user_id, 'formulario_field_id' => $field->id])->first();
                            @endphp
                            <tr>
                                <td>{{ $field->field_name }}
                                </td>
                                <td>
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="permitirDescarga{{ $field->id }}" name="permitirDescarga[]" value="{{ $field->id }}" {{ $getConfigField != null ? 'checked' : null}}>
                                        <label class="form-check-label" for="permitirDescarga{{ $field->id }}">SÍ</label>
                                      </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
                @endhasrole
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 mt-3">
                <h5>ESTUDIOS</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescargaEstudios" id="imagenes1" value="1"
                    @if ($permisionDownloadStudy == 1)
                        {{ 'checked'}}
                    @endif
                    >
                    <label class="form-check-label" for="imagenes1">
                        CON IMÁGENES
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="permisosDescargaEstudios" id="sinImagenes2" value="0"
                    @if ($permisionDownloadStudy == 0)
                        {{ 'checked'}}
                    @endif
                    >
                    <label class="form-check-label" for="sinImagenes2">
                        SIN IMÁGENES
                    </label>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 text-right">
            <button class="btn btn-primary">Guardar</button>
        </div>
        <input type="hidden" name="id" value="{{ $userId }}">
    </form>
</div>
@stop
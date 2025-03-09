@extends('layouts.template')
@inject('MComments', 'App\Models\Comment')
@section('content')
<div class="container bg-white py-2">
    @php
    $total = $solicitud->precio * $solicitud->cantidad;

    
    
@endphp
    @hasrole(['administrador'])
       
        @if ($solicitud->nombre == 'Paquete básico' || $solicitud->nombre == 'Usuario extra' | $solicitud->nombre == 'consultorio extra' )
            
        <div class="row">
           
            <div class="col-6">
                <div class="row">
                    <div class="col-12 ">
                        <h5>ETAPAS</h5>
                    </div>
                </div>
                <div class="card"  style="min-height: 170px">
                    
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td> 1- Validar información de la solicitud</td>
                                <td>
                                    @if ($solicitud->is_cedula_valid == null)
                                        <span class="badge badge-dim bg-warning">
                                            <span>En espera </span>
                                        </span>
                                    @endif
                                    
                                    @if ($solicitud->is_cedula_valid == 1)
                                        <span class="badge badge-dim bg-success">
                                            <span>Concluido </span>
                                        </span>
                                    @endif
                                   
                                </td>
                                <td>
                                    <a href="/admin/solicitudes/{{ $solicitud->id }}/task/1" class="btn btn-sm btn-primary">Abrir</a>
                                  
                                </td>
                            </tr>
                            <tr>
                                <td> 2- Validar Comprobante pago</td>
                                <td>
                                    @if ($solicitud->is_cedula_valid ==  1 && $solicitud->estatus != 1)
                                    <span class="badge badge-dim bg-warning">
                                        <span>En espera </span>
                                    </span>
                                    @endif
                                    @if ($solicitud->estatus == 1)
                                    <span class="badge badge-dim bg-success">
                                        <span> Concluido </span>
                                    </span>
                                    @endif
                                </td>
                                <td><a href="/admin/solicitudes/{{ $solicitud->id }}/task/2" class="btn btn-sm btn-primary">Abrir</a></td>
                            </tr>
                            
                            
                        </table>
                    </div>
                </div>
            </div>    
            
            <div class="col-6">
                <div class="row">
                    <div class="col-12 ">
                        <h5>VINCULAR</h5>
                    </div>
                </div>
                <div class="card">
                   
                    <div class="card-body">
                        <table class="table table-borderless">
                            @if ($solicitud->nombre == 'Paquete básico' || $solicitud->nombre == 'clinica extra')
                                <tr>
                                    <td>CLINICA</td>
                                    <td> <a href="#"  data-bs-toggle="modal" data-bs-target="#modalClinica"><i class="fas fa-plus"></i></a> </td>
                                </tr>
                            @endif
                            @if ($solicitud->nombre == 'Paquete básico' || $solicitud->nombre == 'consultorio extra')
                                <tr>
                                    <td>CONSULTORIO</td>
                                    <td><a href="#"  data-bs-toggle="modal" data-bs-target="#modalConsultorio"><i class="fas fa-plus"></i></a></td>
                                </tr>
                            @endif
                            @if ($solicitud->nombre == 'Paquete básico' || $solicitud->nombre == 'Usuario extra')
                                <tr>
                                    <td>USUARIO</td>
                                    <td><a href="#"  data-bs-toggle="modal" data-bs-target="#modalUsuario"><i class="fas fa-plus"></i></a></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
               
            </div>
             <div class="row mt-3">
                    <div class="col-12 ">
                        <h5>VINCULACIÓNES</h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Clínica</th>
                                    <th>Consultorio</th>
                                    <th>Usuarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxRows = max(count($getVinculacion['clinica']), count($getVinculacion['consultorio']), count($getVinculacion['usuarios']));
                                @endphp
                        
                                @for ($i = 0; $i < $maxRows; $i++)
                                    <tr>
                                        <td>{{ $getVinculacion['clinica'][$i] ?? '' }}</td>
                                        <td>{{ $getVinculacion['consultorio'][$i] ?? '' }}</td>
                                        <td>{{ $getVinculacion['usuarios'][$i] ?? '' }}</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>     
        @endif
    @endrole
        <div class="card-body">

            <div class="col-12 mt-3">
                
                
                   
                    <table class="table table-borderless">
                        

                        <tr>
                            <td colspan="2">
                                <p class="h6 color-secondary">SOLICITUD</p>
                            </td>
                        </tr>
                        <tr>
                            <td>PAQUETE</td>
                            <td>{{ $solicitud->nombre }}</td>
                        </tr>
                        @foreach ($pacientes as $pacientesValue)
                            <tr>
                                <td> NOMBRE PACIENTE </td>
                                <td>{{ $pacientesValue->paciente->name }}  {{ $pacientesValue->paciente->vapellido }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>CANTIDAD</td>
                            <td>{{ $solicitud->cantidad }}</td>
                        </tr>
                        <tr>
                            <td>PRECIO</td>
                            <td>${{ format_price($solicitud->precio) }}</td>
                        </tr>
                        
                        <tr>
                            <td>TOTAL</td>
                            <td>${{ format_price($total) }}
                              
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"> <hr> </td>
                        </tr>
                        @hasrole(['medico'])
                        <tr>
                            <td colspan="2"> <p class="h6 color-secondary">DATOS PARA LA TRANSFERENCIA</p></td>
                        </tr>
                        <tr>
                            <td>NOMBRE</td>
                            <td>JOSE VAZQUEZ</td>
                        </tr>
                        <tr>
                            <td>CLABE</td>
                            <td>0123348458585858</td>
                        </tr>
                        <tr>
                            <td>BANCO</td>
                            <td>BANAMEX</td>
                        </tr>
                        <tr>
                            <td>CANTIDAD A DEPOSITAR</td>
                            <td>${{ format_price($total) }}</td>
                        </tr>
                        @endrole
                    </table>
                    
               
               
                    
                @hasrole(['medico'])
                <p class="h6 color-secondary mt-5">SUBIR COMPROBANTE DE LA TRANSFERENCIA</p>
                <form method="post" action="/admin/solicitudes/{{ $id }}/adjuntarComprobante" enctype="multipart/form-data">
                    @csrf
                    @php
                        $pathComprobante = env('PATH_COMPROBANTE')
                    @endphp
                    <div class="mb-3 mt-3">
                        <label for="inputNombre" class="form-label">COMPROBANTE</label>
                        @if ($solicitud->comprobante == null)
                            <input type="file" name="comprobante" class="form-control">
                            @else
                            <div class="col-12">
                                <img class="previewComrobante" src="{{ asset($pathComprobante).'/'.$solicitud->comprobante }}" alt="">
                                <div class="col-12 mt-3">
                                    <a href="{{ asset($pathComprobante).'/'.$solicitud->comprobante }}" target="_blank" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                    <a href="/admin/solicitudes/{{ $solicitud->id }}/imagen/delete" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endrole
                    @hasrole(['administrador'])
                        {{-- @if ($solicitud->solicitud_origin_id == 1)
                            <div class="mb-3 mt-3">
                            
                                <label for="inputNombre" class="form-label">ASIGNAR CLINICA</label>
                                <select name="clinica" id="" class="form-control select2multiple"   data-search="on" multiple="multiple">
                                    @foreach ($clinicas as $clinica)
                                        <option value="{{ $clinica->idclinica }}" 
                                         @foreach ($my_clinics as $my_clinic)
                                           {{ $my_clinic->clinica_id == $clinica->idclinica ? 'selected' : null}}
                                         @endforeach
                                         >{{ $clinica->tnombre }}</option>
                                    @endforeach
                                 </select>

                            </div>
                        @endif --}}
                       {{--  <div class="mb-3 mt-3">
                        
                            <label for="inputNombre" class="form-label">FECHA DE VENCIMIENTO</label>
                            <input type="date" class="form-control" name="fecha_vencimiento" value="{{ $fecha_vencimiento }}">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="inputNombre" class="form-label">ACTIVAR</label>
                            <select name="estatus" id="estatus" class="form-control">
                                <option value="">Seleccione una opción</option>
                                <option value="1" {{ $solicitud->estatus == 1 ? 'selected' : null}}>SÍ</option>
                                <option value="0" {{ $solicitud->estatus == 0 ? 'selected' : null}}>NO</option>
                                <option value="2" {{ $solicitud->estatus == 2 ? 'selected' : null}}>EN REVISIÓN</option>
                            </select>
                        </div> --}}
                        
                    @endrole

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    
                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            <input type="hidden" name="solicitudId" id="solicitudId" value="{{ $id }}">
                            <a class="btn btn-secondary" onclick="comentar(null)">Comentar</a>
                            @hasrole(['medico', 'auxiliar'])
                                <button class="btn btn-primary">Adjuntar</button>
                            @endrole
                           
                        </div>
                    </div>
                </form>
                
                <div class="col-12 ">
                    <h5 class="text-secondary mb-4 ">COMENTARIOS</h5>
                    @foreach ($comments as $comment)
                    @php
                        $respuestas = $MComments::where([
                            'type' => 3,
                            'respuesta_id' => $comment->id,
                        ])->get();
                    @endphp
                    <div class="card my-3 shadow-sm">
                        <div class="card-body">
                            <!-- Pregunta Principal -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary font-weight-bold">{{ $comment->user }}</span>
                                <span class="text-muted small">{{ $comment->created_at }}</span>
                            </div>
                            <p class="mb-3">{{ $comment->comment }}</p>
                            <div class="col-12 text-end">
                                <button class="btn btn-outline-secondary btn-sm" onclick="comentar({{ $comment->id }})">Responder</button>
                            </div>
                        </div>
                
                        <!-- Respuestas -->
                        @if(!empty($respuestas))
                            <div class="bg-light p-3">
                                @foreach ($respuestas as $respuesta)
                                <div class="card my-2 border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-secondary font-weight-bold">{{ $respuesta->user }}</span>
                                            <span class="text-muted small">{{ $respuesta->created_at }}</span>
                                        </div>
                                        <p class="mb-0">{{ $respuesta->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="commentSolicitudModal" tabindex="-1" aria-labelledby="commentSolicitudModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" id="frm-solicitud-comentario">
        
            <div class="modal-header">
            <h5 class="modal-title" id="commentSolicitudModalTitle">Comentario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="inputApellido" class="form-label">*COMENTARIO</label>
                        <textarea name="data[comment]" id="commentario" cols="5" rows="10" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="hidden" name="commentId" id="commentId" value="">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Comentar</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  {{-- modal comment --}}

{{-- modal clinica --}}
<div class="modal fade" id="modalClinica" tabindex="-1" aria-labelledby="modalClinicaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="frm-vincular-clinica">
            <div class="modal-header">
              <h5 class="modal-title" id="modalClinicaLabel">Vincular clinica</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">SELECCIONE UNA CLINICA</label>
                    <select name="clinica" class="form-control">
                        @foreach ($clinicasVincular as $clinicaVincular)
                            <option value="{{ $clinicaVincular->id }}"> {{ $clinicaVincular->nombre }} </option>
                        @endforeach
                    </select>
                  </div>
            </div>
            <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Vincular</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- /modal clinica --}}

{{-- modal consultorio --}}
<div class="modal fade" id="modalConsultorio" tabindex="-1" aria-labelledby="modalConsultorioLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="frm-vincular-consultorio">
            <div class="modal-header">
              <h5 class="modal-title" id="modalConsultorioLabel">Vincular consultorio</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">SELECCIONE UN CONSULTORIO</label>
                    <select name="consultorio" class="form-control">
                        @foreach ($consultorioVincular as $consultorioVincular)
                            <option value="{{ $consultorioVincular->idconsultorios }}"> {{ $consultorioVincular->vnumconsultorio }} </option>
                        @endforeach
                    </select>
                  </div>
            </div>
            <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Vincular</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- /modal clinica --}}

{{-- modal usuario --}}
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="frm-vincular-usuario">
            <div class="modal-header">
              <h5 class="modal-title" id="modalUsuarioLabel">Vincular usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">SELECCIONE UN USUARIO</label>
                    <select name="usuario" class="form-control">
                        @foreach ($usuarioVincular as $usuarioVincular)
                        <option value="{{ $usuarioVincular->id }}"> {{ $usuarioVincular->name }} {{ $usuarioVincular->vapellido }} {{ $usuarioVincular->segundo_apellido }} </option>
                    @endforeach
                    </select>
                  </div>
            </div>
            <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Vincular</button>
            </div>
        </form>
      </div>
    </div>
  </div>
{{-- /modal clinica --}}
@endsection
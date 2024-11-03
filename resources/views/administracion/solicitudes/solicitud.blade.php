@extends('layouts.template')
@inject('MComments', 'App\Models\Comment')
@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3 card">
       
        <div class="card-body">

            <div class="col-12 mt-3">
                <p class="h6 color-secondary">SOLICITUD</p>
                <p>
                     <span class="color-secondary"> PAQUETE :</span>  {{ $solicitud->nombre }}  <br>
                     <span class="color-secondary"> CANTIDAD :</span>  {{ $solicitud->cantidad }}  <br>
                     <span class="color-secondary"> PRECIO :</span>  {{ $solicitud->precio }}  <br>
                     <span class="color-secondary"> TOTAL :</span>  {{ $solicitud->precio }}  <br>
                </p>
                <p class="h6 color-secondary">DATOS PARA LA TRANSFERENCIA</p>
                <span class="color-secondary"> NOMBRE :</span> JOSE VAZQUEZ  <br>
                <span class="color-secondary"> CLABE :</span> 0123348458585858  <br>
                <span class="color-secondary"> BANCO :</span> BANAMEX  <br>
                
                <p class="h6 color-secondary mt-3">SUBIR COMPROBANTE DE LA TRANSFERENCIA</p>
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
                                    <a href="" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                        @endif
                    </div>
                    @hasrole(['administrador'])
                        <div class="mb-3 mt-3">
                        
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
                        </div>
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
                            @hasrole('administrador')
                                <button class="btn btn-primary">Guardar</button>
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
                            'idRel' => $comment->id,
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
                                <button class="btn btn-outline-secondary btn-sm" onclick="comentar({{ $comment->idRel }})">Responder</button>
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

@endsection
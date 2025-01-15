@extends('layouts.template')
@inject('MComments', 'App\Models\Comment')
@section('content')
@php
    $subtotal = $solicitud->precio * $solicitud->cantidad;

    if ($solicitud->catalog_prices_id == 1 || $solicitud->catalog_prices_id == 4) {
        $total = $subtotal;
    } else {
        $total = $subtotal + $paqueteActivo;
    }
@endphp
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"> <a href="/admin/solicitudes/{{ $solicitud->id }}"> ETAPAS </a> </li>
                        <li class="breadcrumb-item"> Validar Comprobante pago </li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <p class  = "h6 color-secondary mt-5">SUBIR COMPROBANTE DE LA TRANSFERENCIA</p>
                <form method = "post" action = "/admin/solicitudes/{{ $solicitud->id }}/adjuntarComprobante"
                    enctype = "multipart/form-data">
                    @csrf
                    @php
                        $pathComprobante = env('PATH_COMPROBANTE');
                    @endphp
                    <div class = "mb-3 mt-3">
                        <label for   = "inputNombre" class = "form-label">COMPROBANTE</label>
                        @if ($solicitud->comprobante == null)
                            <input type = "file" name = "comprobante" class = "form-control">
                        @else
                            <div class = "col-12">
                                <img class = "previewComrobante" src = "{{ asset($pathComprobante) . '/' . $solicitud->comprobante }}"
                                    alt = "">
                                <div class = "col-12 mt-3">
                                    <a href  = "{{ asset($pathComprobante) . '/' . $solicitud->comprobante }}" target = "_blank"
                                        class                                                  = "btn btn-primary"><i
                                            class = "fas fa-eye"></i></a>
                                    <a href  = "" class   = "btn btn-danger"><i
                                            class                                       = "fas fa-trash"></i></a>
                                </div>
                            </div>
                        @endif
                    </div>
                    @hasrole(['administrador'])
                        {{-- @if ($solicitud->catalog_prices_id == 1)
                                        <div class = "mb-3 mt-3">
                                        
                                            <label  for  = "inputNombre" class = "form-label">ASIGNAR CLINICA</label>
                                            <select name = "clinica" id        = "" class = "form-control select2multiple"   data-search = "on" multiple = "multiple">
                                                @foreach ($clinicas as $clinica)
                                                    <option value = "{{ $clinica->idclinica }}"
                                                     @foreach ($my_clinics as $my_clinic)
                                                       {{ $my_clinic->clinica_id == $clinica->idclinica ? 'selected' : null}}
                                                     @endforeach
                                                     >{{ $clinica->tnombre }}</option>
                                                @endforeach
                                             </select>
            
                                        </div>
                                    @endif --}}
                        <div class = "mb-3 mt-3">
            
                            <label for  = "inputNombre" class = "form-label">FECHA DE VENCIMIENTO</label>
                            <input type = "date" class        = "form-control" name = "fecha_vencimiento"
                                value = "{{ $fecha_vencimiento }}">
                        </div>
                        <div class = "mb-3 mt-3">
                            <label for   = "inputNombre" class = "form-label">ACTIVAR</label>
                            <select name  = "estatus" id        = "estatus" class = "form-control">
                                <option value = "">Seleccione una opción</option>
                                <option value = "1" {{ $solicitud->estatus == 1 ? 'selected' : null }}>SÍ</option>
                                <option value = "4" {{ $solicitud->estatus == 0 ? 'selected' : null }}>NO</option>
                                <option value = "0" {{ $solicitud->estatus == 2 ? 'selected' : null }}>EN REVISIÓN</option>
                            </select>
                        </div>
                    @endrole
            
                    @if ($errors->any())
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            
                    @if (session('success'))
                        <div class = "alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            <input type="hidden" name="solicitudId" id="solicitudId" value="{{ $solicitud->id }}">
                            @hasrole('administrador')
                                <a href="/admin/solicitudes/{{ $solicitud->id }}" class="btn btn-success">Volver</a>
                                <button class="btn btn-primary">Guardar</button>
                            @endrole
                        </div>
                    </div>
            
                    <input type="hidden" name="precio_total" value="{{ $total }}" >
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE PENDIENTES</li>
                    <li class="breadcrumb-item">PENDIENTE</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3">
       
    
        <div class="col-12 mt-3">
            <form method="post" action="/admin/usuarios" id="frm-pendiente">
           {{--  @if ($user_id == null)
            @else
            <form method="post" action="/admin/usuarios" id="upd-frm-users">
            @endif --}}
                <div class="col-12">
                    <p class="text-info">Los campos marcados con * son requeridos</p>
                </div>
               
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputRecordatorio" class="form-label">*RECORDATORIO</label>
                            <textarea name="data[pendiente]" id="inputRecordatorio" cols="30" rows="4" class="form-control" required>{{ $pendiente != null ? $pendiente->pendiente : null }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputFecha" class="form-label">*FECHA</label>
                            <input type="date" class="form-control" name="data[fecha]" id="inputFecha" value="{{ $pendiente != null ? $pendiente->fecha : null }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputHora" class="form-label">HORA</label>
                            <input type="text" class="form-control" name="data[hora]" id="inputHora" value="{{ $pendiente != null ? $pendiente->hora : null }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputEstatus" class="form-label">*ACTIVO</label>
                            <select name="data[status]" id="inputEstatus" class="form-control" required>
                                
                               @foreach (config('enums.status') as $key => $item)
                                   <option value="{{ $key }}" {{ $pendiente != null && $pendiente->status == $key ? 'selected' : null  }}>{{ $item }}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-12 text-right">
                        <div class="mb-3">
                            <input type="hidden" id="pendiente_id" name="pendiente_id" value="{{ $pendiente_id }}" >
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>


@endsection
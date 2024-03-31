@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">USUARIOS</li>
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
            <form method="post" action="/admin/usuarios" id="frm-user-add-office">
                @csrf
           {{--  @if ($user_id == null)
            @else
            <form method="post" action="/admin/usuarios" id="upd-frm-users">
            @endif --}}
                <div class="col-12">
                    <p class="text-info">Los campos marcados con * son requeridos</p>
                </div>
                <div class="col-12 py-3 mt-3">
                    <p class="lead">DATOS GENERALES</p>
                </div>
                @csrf
                <div class="row">
                    <div class="col-md-6" id="content-financial_product_id">
                        <div class="form-group">
                            <label class="form-label">CONSULTORIO</label>
                            <div class="form-control-wrap">
                                <select name="data[idconsultorio]" id="offices"  onchange="changeOffice(this.value)" class="form-control">
                                    <option value="">Seleccione una opción</option>
                                   @foreach ($offices as $office)
                                       <option value="{{ $office->idconsultorios }}">{{ $office->vnumconsultorio }}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6>SELECCIONE LAS HORAS DE CONSULTA</h6>
                    </div>

                    <div class="col-12" id="content-horario-consulta">

                    </div>
                    
                    <div id="content-duracion-consulta" style="display: none">
                        <div class="col-12">
                            <h6>SELECCIONE CUANTO TIEMPO DURA LA CONSULTA</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">DURACIÓN CONSULTA</label>
                                <div class="form-control-wrap">
                                    <select name="duraconsulta" id="duraconsulta" class="form-control">
                                        <option value="15">15 MINUTOS</option>
                                        <option value="20">20 MINUTOS</option>
                                        <option value="30">30 MINUTOS</option><!--
                                        <option value="40">40 MINUTOS</option>
                                        <option value="50">50 MINUTOS</option>
                                        <option value="60">60 MINUTOS</option>-->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-12 text-right">
                        <div class="mb-3">
                            {{-- <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}" > --}}
                            <input name="tipo" id="tipo" value="1" type="hidden" />
                            <input name="medicocon" id="medicocon" value="{{ $myUser->id }}" type="hidden" />
                            <input name="asignarp" id="asignarp" value="1" type="hidden" />
                            <button class="btn btn-primary" id="btn-add-office-user">Guardar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>


@endsection
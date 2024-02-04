@extends('adminlte::page')
@section('content_header')
    <div class="container">
        <div class="row mt-3">

            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"> <a href="">LISTA DE CITAS</a> </li>
                        {{-- <li class="breadcrumb-item active" aria-current="page"><a href="/">CITA</a></li> --}}
                        <li class="breadcrumb-item active">CITAS</li>
                    </ol>
                </nav>
            </div>



        </div>
    </div>
@stop


@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3 justify-content-center">
            <div class="col-6">
                <form action="" method="GET" id="frm-cita">
                    @csrf
                    <div class="form-group row">
                        <label for="fe_inicio" class="col-sm-4 col-form-label">DÍA DE LA CITA</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="fe_inicio"
                                name="data[dfecha]" value="{{ $fe_inicio }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hora" class="col-sm-4 col-form-label">HORA SELECCIONADA:</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="hora" name="data[vhora]"
                                value="{{ $horas }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="selectPaciente" class="col-sm-4 col-form-label">PACIENTE:</label>
                        <div class="col-sm-8">
                            <select name="data[idcliente]" id="selectPaciente" class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="hora" class="col-sm-4 col-form-label">MOTIVO DE CONSULTA:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="data[tmotivoconsulta]" id="" cols="30" rows="4"></textarea>
                        </div>
                        <input type="hidden" name="id_cita" value="{{ $id_cita }}">
                        <input type="hidden" name="data['lidldoctores']" value="{{ $lidldoctores }}">
                        <input type="hidden" name="idconsultorio" value="{{ $idconsultorio }}">
                        <input type="hidden" name="data[idiasemana]" value="{{ $idia }}">
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Guardar</button>
                </form>
            </div>
        </div>
    </div>
@stop

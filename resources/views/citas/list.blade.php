@extends('adminlte::page')
@section('content_header')
    <div class="container">
        <div class="row mt-3">
           
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE USUARIOS</li>
                       {{--  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                     <li class="breadcrumb-item ">CITAS</li> --}}
                    </ol>
                </nav>
            </div>

            <div class="col-12">
                <form class="row mt-3" method="POST">
                    <div class="col-auto">
                        <label for="staticEmail2" class="col-form-label">Clinica</label>
                    </div>
                    <div class="col-4">
                      <select name="clinica" id="" class="form-control">
                        <option value="">Selecciona una opción</option>
                        @foreach ($clinicas as $clinica)
                            <option value="{{ $clinica->idclinica }}"> {{ $clinica->tnombre }} </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-auto">
                        <label for="staticEmail2" class="col-form-label">Consultorio</label>
                    </div>
                    <div class="col-4">
                        <select name="consultorio" id="" class="form-control">
                            <option value="">Selecciona una opción</option>
                            @foreach ($consultorios as $consultorio)
                                <option value="{{ $consultorio->idconsultorios }}"> {{ $consultorio->vnumconsultorio }} </option>
                            @endforeach
                          </select>
                      </div>
                    <div class="col-auto">
                      <button type="submit" class="btn btn-primary mb-3">Seleccionar</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
@stop


@section('content')


<div class="container bg-white py-2">
    <div class="row mt-3">
        <div class="col-12 text-right">
            <a href="/admin/citas/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
        </div>

        <div class="col-12">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

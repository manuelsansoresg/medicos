@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item ">CITAS</li>
                </ol>
            </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="fw-bold">PANEL DE ADMINISTRACIÓN DE CITAS</p>
            </div>

        </div>
    </div>
    @if ($is_medico != null)
    <div class="col-12">
        No tiene asignado ningun consultorio 
    </div>
    @endif
   
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
          <button type="submit" class="btn btn-primary mb-3">Filtrar</button>
        </div>
    </form>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table">
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
@endsection

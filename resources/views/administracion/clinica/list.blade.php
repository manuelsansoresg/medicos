@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item"><a href="/admin/administracion">ADMINISTRACIÓN</a></li>
                  <li class="breadcrumb-item">LISTA DE CLINICAS</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <a href="/admin/clinica/create" class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i></a>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>RFC</th>
                        <th>DIRECCIÓN</th>
                        <th>TELÉFONO</th>
                        <th>FOLIO</th>
                        <th>ACTIVO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clinicas as $clinica)
                        <tr>
                            <td>{{ $clinica->tnombre}}</td>
                            <td> {{ $clinica->vrfc }} </td>
                            <td> {{ $clinica->tdireccion }} </td>
                            <td> {{ $clinica->ttelefono }} </td>
                            <td> {{ $clinica->vfolioclinica }} </td>
                            <td> {{ config('enums.status')[$clinica->istatus] }} </td>
                            <td class="col-2">
                                <a href="/admin/clinica/{{ $clinica->idclinica }}/edit" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#" onclick="deleteClinica({{ $clinica->idclinica }})"  class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
@endsection
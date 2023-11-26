@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item"><a href="/admin/administracion">ADMINISTRACIÓN</a></li>
                  <li class="breadcrumb-item">LISTA DE USUARIOS</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <a href="/admin/usuarios/create" class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i></a>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name}}</td>
                            <td> {{ $user->email }} </td>
                            <td class="col-2">
                                <a href="/admin/usuarios/{{ $user->id }}/edit" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#" onclick="deleteUser({{ $user->id }})" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
@endsection
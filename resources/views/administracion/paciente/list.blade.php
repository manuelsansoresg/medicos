@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE PACIENTES</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container bg-white py-2">
        <div class="row mt-3 card">
            <div class="card-body">

                <div class="col-12 text-end">
                    <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                    <a href="/admin/pacientes/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
                <div class="col-12">
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
                                    <td>{{ $user->name }} {{  $user->vapellido }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td class="col-3">
                                        <a href="/admin/pacientes/{{ $user->id }}/edit" class="btn btn-primary"><i
                                                class="fas fa-edit"></i></a>
                                        <a href="#" onclick="deletePaciente({{ $user->id }})" class="btn btn-danger"><i
                                                class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

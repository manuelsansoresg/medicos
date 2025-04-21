@extends('layouts.template')
@inject('MmSolicitudPaciente', 'App\Models\SolicitudPaciente') 
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
                    @hasrole(['administrador'])
                    <a href="/admin/pacientes/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @endrole
                    @if ($statusPackages['totalPacientes']['isLimit']  == false)
                        <a href="/admin/pacientes/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @endif
                    
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
                                @php
                                    $isExistPacientePermissions = $MmSolicitudPaciente::where('paciente_id', $user->id)->count();
                                    $isLinked = $user->isLinkedToMyList($user->id);
                                @endphp
                                <tr>
                                    <td>{{ $user->name }} {{  $user->vapellido }} 
                                        @if ($isLinked)
                                            <span class="badge bg-success me-2">
                                                <i class="fas fa-link"></i> Vinculado
                                            </span>
                                        @endif    
                                    </td>
                                    <td> {{ $user->email }} </td>
                                    <td class="col-3">
                                        @if ($isLinked)
                                        <a href="/admin/pacientes/{{ $user->id }}" class="btn btn-primary"><i
                                            class="fas fa-eye"></i></a>
                                            <a href="#" onclick="deleteVinculo({{ $user->id }})" class="btn btn-danger"><i
                                                class="fas fa-trash"></i></a>
                                        @else
                                            @if ($isExistPacientePermissions > 0)
                                            <a href="#" onclick="permisosPaciente({{ $user->id }})" class="btn btn-success"><i
                                                class="fas fa-users-cog"></i></a>
                                            @endif
                                    
                                            <a href="/admin/pacientes/{{ $user->id }}/edit" class="btn btn-primary"><i
                                                    class="fas fa-edit"></i></a>

                                            <a href="#" onclick="deletePaciente({{ $user->id }})" class="btn btn-danger"><i
                                                    class="fas fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalPermisosPaciente" tabindex="-1" aria-labelledby="modalPermisosPacienteLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="frm-config-download-pacient-expedient">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPermisosPacienteLabel">Agregar permisos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="content-pacient-permissions">
    
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>

@endsection

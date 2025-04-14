@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE CONSULTORIOS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@inject('Solicitud', 'App\Models\Solicitud')
@inject('User', 'App\Models\User')

@section('content')

<div class="container bg-white py-2">
    <div class="row mt-3 card">
        <div class="card-body">

            <div class="col-12 text-end">
                <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                @hasrole(['administrador'])
                    <a href="/admin/consultorio/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                @endrole
                @if ($getUsedStatusPackages['totalConsultorioExtra']['isLimit']  == false)
                    {{-- Cuando se quiera asignar un consultorio verificar que este validado el usuario --}}
                    @php
                        $solicitud = $Solicitud::getMyPackage();
                        $user = $User::find(Auth::user()->id);
                    @endphp
                    @if ($solicitud != null && $solicitud->isValidateCedula != 1)
                    <a href="/admin/consultorio/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @endif
                    @if ( $solicitud != null && $solicitud->isValidateCedula == 1 && $user->is_cedula_valid)
                    <a href="/admin/consultorio/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @endif
                @endif
            </div>
            <div class="col-12">
                
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>UBICACIÓN</th>
                            <th>TELÉFONO</th>
                            <th>CLINICA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($query as $query)
                            <tr>
                                <td> {{ $query->vnumconsultorio }} </td>
                                <td> {{ $query->thubicacion }} </td>
                                <td> {{ $query->ttelefono }} </td>
                                <td> 
                                    @php
                                        $clinica = $query->consultorioClinica;
                                    @endphp
                                    {{ @$clinica!= null ? $clinica->tnombre : null }} 
                                </td>
                                <td class="col-2">
                                    <a href="/admin/consultorio/{{ $query->idconsultorios }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteConsultorio({{ $query->idconsultorios }})" class="btn btn-danger"><i
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

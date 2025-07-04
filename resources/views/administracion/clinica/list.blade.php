@extends('layouts.template')


@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">LISTA DE CLINICAS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@inject('Solicitud', 'App\Models\Solicitud')
@inject('User', 'App\Models\User')

@section('content')
@php
    $solicitud = $Solicitud::getMyPackage();
    $user = $User::find(Auth::user()->id);
@endphp
<div class="container bg-white py-2">
    <div class="row mt-3 card">
        <div class="card-body">

            <div class="col-12 text-end">
                <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                @hasrole(['administrador'])
                
                    <a href="/admin/clinica/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    @else
                        @if (isset($getUsedStatusPackages['totalClinica']['isLimit']) && $getUsedStatusPackages['totalClinica']['isLimit']  == false)
                        {{-- Cuando se quiera asignar un consultorio verificar que este validado el usuario --}}
                        @php
                            $solicitud = $Solicitud::getMyPackage();
                            $user = $User::find(Auth::user()->id);
                        @endphp
                        @if ($solicitud != null && $solicitud->isValidateCedula != 1)
                        <a href="/admin/clinica/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                        @endif
                        @if ( $solicitud != null && $solicitud->isValidateCedula == 1 && $user->is_cedula_valid)
                        <a href="/admin/clinica/create" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                        @endif
                    @endif

                @endrole

                
                
               
            </div>
            <div class="col-12">
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
                                <td>{{ $clinica->tnombre }}</td>
                                <td> {{ $clinica->vrfc }} </td>
                                <td> {{ $clinica->tdireccion }} </td>
                                <td> {{ $clinica->ttelefono }} </td>
                                <td> {{ $clinica->vfolioclinica }} </td>
                                <td> {{ config('enums.status')[$clinica->istatus] }} </td>
                                <td class="col-2">
                                    <a href="/admin/clinica/{{ $clinica->idclinica }}/edit" class="btn btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteClinica({{ $clinica->idclinica }})" class="btn btn-danger"><i
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

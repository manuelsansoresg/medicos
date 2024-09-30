@extends('adminlte::page')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-8">
           <h4> ETAPAS <span>0%</span></h4>
        </div>
        <div class="col-4">
            <a href=""><i class="fas fa-bell"></i></a>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container bg-white py-2">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <th>SECCIÓN</th>
                    <th>ESTATUS</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    <tr>
                        <td>CLINICAS</td>
                        <td> 
                            @if ($statusClinic == true)
                                <span class="text-success">CONCLUIDO</span>
                            @else
                            <span class="text-warning">EN CURSO</span>
                            @endif
                         </td>
                        <td>
                            <a href="/admin/clinica" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                             <a href="/admin/clinica/create" target="_blank" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                         </td>
                    </tr>
                    <tr>
                        <td>CONSULTORIOS</td>
                        <td>
                            @if ($statusConsult == true)
                                <span class="text-success">CONCLUIDO</span>
                            @else
                            <span class="text-warning">EN CURSO</span>
                            @endif
                        </td>
                        <td> 
                            <a href="/admin/consultorio" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                            <a href="/admin/consultorio/create" target="_blank" class="btn btn-primary"><i class="fas fa-plus"></i></a> 
                        </td>
                    </tr>
                    <tr>
                        <td>USUARIOS</td>
                        <td>
                            @if ($statusUser == true)
                                <span class="text-success">CONCLUIDO</span>
                            @else
                            <span class="text-warning">EN CURSO (FALTA ASIGNAR CONSULTORIO)</span>
                            @endif
                        </td>
                        <td> 
                            <a href="/admin/usuarios" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                            <a href="/admin/usuarios/create" target="_blank" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                         </td>
                    </tr>
                    <tr>
                        <td>PACIENTES</td>
                        <td>
                            @if ($statusPacient == true)
                                <span class="text-success">CONCLUIDO</span>
                            @else
                            <span class="text-warning">EN CURSO</span>
                            @endif
                        </td>
                        <td> 
                            <a href="/admin/usuarios" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                            <a href="/admin/usuarios/create" target="_blank" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                         </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
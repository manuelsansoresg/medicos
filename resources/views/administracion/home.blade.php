@extends('layouts.template')



@section('content')
<div class="container bg-white py-2">
    
    <div class="row mt-2">
       <div class="col-12 text-center">
        <h6 class="color-secondary">PANEL PRINCIPAL</h6>
       </div>
        <!-- Primera columna -->
        @hasrole(['medico','auxiliar'])
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-body flex-grow-1">
                    <h6 class="card-title text-center">CONFIGURACIÓN DEL SISTEMA</h6>
                    <div class="row">
                        <div class="col-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td>CLINICAS</td>
                                    <td><a href="/admin/clinica"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/clinica/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td>CONSULTORIOS</td>
                                    <td><a href="/admin/consultorio"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/consultorio/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td>USUARIOS</td>
                                    <td><a href="/admin/usuarios"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/usuarios/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td>PACIENTES</td>
                                    <td><a href="/admin/pacientes"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/pacientes/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-4">
                            <div id="container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Segunda columna -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-body flex-grow-1">
                    <h6 class="card-title text-center">PENDIENTES 
                        <a href="admin/pendientes/create" target="_blank" class="color-primary"><i class="fas fa-plus"></i></a> 
                        <a href="/admin/pendientes" target="_blank" class="color-primary"><i class="fas fa-table"></i></a>
                    </h6>
                    
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-borderless">
                                @foreach ($earrings as $earring)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($earring->pendiente, 40) }}
                                    </td>
                                    <td><a href="/admin/clinica" target="_blank" class="color-primary"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- tercera columna -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-body flex-grow-1">
                    <h6 class="card-title text-center">CITAS <a href="/admin/clinica/create" target="_blank" class="color-primary"><i class="fas fa-plus"></i></a> </h6>
                    <div class="row">
                        <div class="col-10">
                            <table class="table table-borderless">
                                @if ($consultas != null)
                                    @foreach ($consultas as $consulta)
                                    <tr>
                                        <td>
                                            DE {{ $consulta->ihorainicial }}:00 HRS. A
                                            {{ $consulta->ihorafinal }}:00 HRS.<br>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                               
                                
                            </table>
                        </div>
                        <div class="col-2 text-center">
                            <a href="/admin/clinica/create" target="_blank" class="color-primary"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        
    </div>
    
    <livewire:solicitudes-livewire :limit="50"  />
   
    <div class="row mt-3">
        <div class="col-12">
            <div class="col-12 text-center">
                <p class="h6"> GANANCIAS </p>
            </div>
            <table class="table">
                <tr>
                    <th>NOMBRE</th>
                    <th>COSTO</th>
                    <th>GANANCIA</th>
                </tr>
                <tr>
                    <td>MANUEL SANSORES MANUEL DE JESUS</td>
                    <td>100</td>
                    <td>50</td>
                </tr>
                <tr>
                    <td>JUAN CENTENO CAMARA</td>
                    <td>100</td>
                    <td>50</td>
                </tr>
            </table>
        </div>
    </div>
    
</div>
@stop
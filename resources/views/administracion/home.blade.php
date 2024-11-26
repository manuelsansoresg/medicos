@extends('layouts.template')



@section('content')
<div class="container bg-white py-2">
    
    <div class="row mt-2">
       <div class="col-12 text-center">
        <h6 class="color-secondary">PANEL PRINCIPAL</h6>
       </div>
       
       @hasrole(['administrador'])
       <div class="row justify-content-center">
            <div class="col-md-2 text-center">
            <div class="card">
                <div class="card-body">
                    <a href="/admin/usuarios"><i class="fas fa-users  fs-3"></i>
                        <br><span>USUARIOS</span>
                    </a>
                    
                </div>
            </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/clinica"><i class="fas fa-clinic-medical fs-3"></i>
                            <br><span>CLÍNICA</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/consultorio"><i class="fas fa-building fs-3"></i>
                            <br><span>CONSULTORIOS</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/paciente"><i class="fas fa-user fs-3"></i>
                            <br><span>PACIENTE</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/expedientes"><i class="fas fa-folder-open fs-3"></i></i>
                            <br><span>EXPEDIENTES</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/template-formulario"><i class="fas fa-edit fs3"></i></i>
                            <br><span>PLANTILLAS</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
           
       </div>
       @endrole
        <!-- Primera columna -->
        @hasrole(['medico','auxiliar'])
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-body flex-grow-1">
                    <h6 class="card-title text-center">CONFIGURACIÓN DEL SISTEMA</h6>
                    <div class="row">
                        <div class="col-8">
                            <table class="table table-borderless">
                                @php
                                    $classClinic = $getPorcentajeSistema['validateClinic'] > 0 ? null : 'text-danger';
                                    $classConsultorios = $getPorcentajeSistema['validateCon'] > 0 ? null : 'text-danger';
                                    $classUsers = $getPorcentajeSistema['validateUsers'] > 0 ? null : 'text-danger';
                                    $classPacient = $getPorcentajeSistema['validatePacient'] > 0 ? null : 'text-danger';
                                    $classTemplate = $getPorcentajeSistema['validateTemplate'] > 0 ? null : 'text-danger';
                                @endphp
                                <tr>
                                    <td><span class="{{ $classClinic }}">CLINICAS</span></td>
                                    <td><a href="/admin/clinica"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/clinica/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td> <span class="{{ $classConsultorios  }}">CONSULTORIOS</span> </td>
                                    <td><a href="/admin/consultorio"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/consultorio/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td><span class="{{ $classUsers }}">USUARIOS</span></td>
                                    <td><a href="/admin/usuarios"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/usuarios/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td><span class="{{ $classPacient }}">PACIENTES</span></td>
                                    <td><a href="/admin/pacientes"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/pacientes/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td><span class="{{ $classTemplate }}">PLANTILLA CONSULTA</span></td>
                                    <td><a href="/admin/template-formulario"  class="color-primary"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="/admin/template-formulario/create"  class="color-primary"><i class="fas fa-plus"></i></a></td>
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
    @hasrole(['medico','auxiliar'])
        <div class="row justify-content-center">
            <div class="col-md-2 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/sin_citas"><i class="fas fa-calendar-day fs-3"></i>
                            <br><span>DÍAS SIN CITAS</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/expedientes"><i class="fas fa-folder-open fs-3"></i>
                            <br><span>EXPEDIENTES</span>
                        </a>
                        
                    </div>
                </div>
            </div>

            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/actividades"><i class="fas fa-check-double fs3"></i>
                            <br><span>ACTIVIDADES</span>
                        </a>
                        
                    </div>
                </div>
            </div>

            <div class="col-md-2  text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="/admin/template-formulario"><i class="fas fa-edit fs3"></i>
                            <br><span>PLANTILLAS</span>
                        </a>
                        
                    </div>
                </div>
            </div>
            
        </div>
       @endrole
       
    <livewire:solicitudes-livewire :limit="50"  />
   
    <livewire:ganancias-livewire :limit="50"  />

   <input type="hidden" id="porcentajeSistema" value="{{ $getPorcentajeSistema['percent'] }}">
    
</div>

<!-- modal reactivacion usuarios y consultorios -->
<div class="modal fade" id="modalReactivacion" tabindex="-1" aria-labelledby="modalReactivacionLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalReactivacionLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modalReactivacionContent"></div>
        </div>
       
      </div>
    </div>
  </div>
@stop
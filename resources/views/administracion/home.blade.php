@extends('layouts.template')

@section('content')
<div class="container bg-white py-3">
    <!-- Usuario Status & Notificaciones -->
    <div class="row mb-4 mt-3">
        
        <div class="col-12">
            <div class="border-0 h-100">
                <div class="bg-white border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Notificaciones</h6>
                        <span class="badge bg-primary rounded-pill">3</span>
                    </div>
                </div>
                <div class=" py-0">
                    <div class="notification-list">
                        <div class="notification-item d-flex align-items-center py-2 border-bottom">
                            <div class="notification-icon bg-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 small">Nueva cita programada para hoy a las 15:00</p>
                                <small class="text-muted">Hace 30 minutos</small>
                            </div>
                        </div>
                        <div class="notification-item d-flex align-items-center py-2 border-bottom">
                            <div class="notification-icon bg-warning rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 small">Recordatorio: Actualizar plantillas médicas</p>
                                <small class="text-muted">Hace 1 hora</small>
                            </div>
                        </div>
                        <div class="notification-item d-flex align-items-center py-2">
                            <div class="notification-icon bg-danger rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div>
                                <p class="mb-0 small">Nuevo paciente registrado: Ana López</p>
                                <small class="text-muted">Hace 3 horas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center">
                    <a href="#" class="text-decoration-none small">Ver todas las notificaciones</a>
                    <hr>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row mt-2">
       <div class="col-12 text-center">
        <h6 class="color-secondary">PANEL PRINCIPAL</h6>
       </div>
       
       @hasrole(['administrador'])
       <div class="row justify-content-center">
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/usuarios" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-users fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">USUARIOS</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/clinica" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-clinic-medical fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CLÍNICA</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/consultorio" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-building fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CONSULTORIOS</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/pacientes" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-user fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">PACIENTE</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/expedientes" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-folder-open fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">EXPEDIENTES</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/template-formulario" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-edit fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">PLANTILLAS</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/catalogo" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-list fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CATALOGO</span>
                        </a>
                    </div>
                </div>
            </div>
           
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/paquete" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-cube fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">PAQUETES</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/setting/create" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-cog fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CONFIGURACIÓN</span>
                        </a>
                    </div>
                </div>
            </div>
       </div>
       @endrole
       
        <!-- Dashboard para médicos y auxiliares -->
        @hasrole(['medico','auxiliar'])
        <div class="row">
            <!-- Widget de Progreso del Paquete -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="card-title text-center m-0">{{ $getPackage->nombre }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="progress-wrapper mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small">Uso del paquete</span>
                                        <span class="badge bg-success">70%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                @foreach ($statusPackages as $statusPackage)
                                <div class="package-feature d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                                    <div>
                                        <i class="fas fa-check-circle text-success me-2"></i> 
                                        <span>{{ $statusPackage['title'] }}</span>
                                    </div>
                                    <span class="badge bg-info">
                                        {{ $statusPackage['lbl'] }}
                                    </span>
                                </div>
                                @endforeach
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title m-0">PENDIENTES</h6>
                        <div>
                            <a href="admin/pendientes/create" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Agregar pendiente">
                                <i class="fas fa-plus"></i>
                            </a>
                            <a href="/admin/pendientes" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver todos">
                                <i class="fas fa-table"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach ($earrings as $earring)
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning me-3">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">{{ \Illuminate\Support\Str::limit($earring->pendiente, 40) }}</p>
                                            <small class="text-muted">Vence: en 3 días</small>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="/admin/clinica" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citas -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title m-0">CITAS DEL DÍA</h6>
                        <a href="/admin/citas" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Agregar cita">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @if ($consultas != null)
                                @foreach ($consultas as $consulta)
                                <a href="/admin/consulta/{{ $consulta->id}}/{{ $consulta->idconsultasignado }}/registro" class="list-group-item list-group-item-action border-0 border-bottom py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="text-primary me-3">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0">De {{ $consulta->ihorainicial }}:00 hrs. a {{ $consulta->ihorafinal }}:00 hrs.</p>
                                                <small class="text-muted">Paciente: María González</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-success">Confirmada</span>
                                    </div>
                                </a>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-check text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No hay citas programadas para hoy</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        <!-- Accesos Rápidos para Médicos y Auxiliares -->
        @hasrole(['medico','auxiliar'])
        <div class="row justify-content-center mt-3">
            <div class="col-12 mb-3">
                <h6 class="border-bottom pb-2">ACCESOS RÁPIDOS</h6>
            </div>

            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/usuarios" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-users fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">USUARIOS</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/clinica" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-clinic-medical fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CLÍNICA</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/consultorio" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-building fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">CONSULTORIOS</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/pacientes" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-user fs-3 text-primary"></i>
                            </div>
                            <span class="fw-bold text-dark">PACIENTE</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/sin_citas" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-calendar-day fs-3"></i>
                            </div>
                            <span class="fw-bold text-dark">DÍAS SIN CITAS</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/expedientes" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-folder-open fs-3"></i>
                            </div>
                            <span class="fw-bold text-dark">EXPEDIENTES</span>
                        </a>
                    </div>
                </div>
            </div>
    
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/actividades" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-check-double fs-3"></i>
                            </div>
                            <span class="fw-bold text-dark">ACTIVIDADES</span>
                        </a>
                    </div>
                </div>
            </div>
    
            <div class="col-md-2 text-center mb-3">
                <div class="card shadow-sm border-0 h-100 transition-hover">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="/admin/template-formulario" class="text-decoration-none">
                            <div class="icon-wrapper mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px">
                                <i class="fas fa-edit fs-3"></i>
                            </div>
                            <span class="fw-bold text-dark">PLANTILLAS</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endrole
       
    <livewire:solicitudes-livewire :limit="50" />
   
    <livewire:ganancias-livewire :limit="50" />

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

<style>
/* Estilos adicionales */
.transition-hover {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.transition-hover:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.card {
  border-radius: 10px;
  overflow: hidden;
}
.notification-list {
  max-height: 300px;
  overflow-y: auto;
}
.icon-wrapper {
  transition: all 0.3s ease;
}
.card:hover .icon-wrapper {
  background-color: #f8f9fa !important;
}
</style>
@stop
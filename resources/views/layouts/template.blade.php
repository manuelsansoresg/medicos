<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel médico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/vendor/EasyAutocomplete/easy-autocomplete.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @livewireStyles

</head>
<body>
  @inject('ClinicaUser', 'App\Models\ClinicaUser')
<style>
    #container {
  margin: 20px;
  width: 100px;
  height: 100px;
  position: relative;
}
</style>
<!-- Menú de navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">LOGO</a>
      
      <a class="nav-link d-lg-none" href="#">
        <i class="fas fa-bell"></i>
      </a>
  
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pacientes</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Configuración
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
         
          <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
          <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              Salir
          </a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="#">
              <i class="fas fa-bell"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<!-- Contenido de la página -->
@php
    $my_clinics = $ClinicaUser::myClinics();
@endphp
<div class="container bg-white py-2">
  <div class="row mt-2">
      <div class="col-12 text-end">
          <h5 class="color-secondary">Bienvenido, {{ Auth::user()->name }}</h5>
      </div>
  </div>

  <form method="post"  id="frm-selection">
    @csrf
    <div class="row">
      <div class="col-12">
        <h5 class="color-secondary">FAVOR DE ELEGIR UNA CLINICA Y UN CONSULTORIO</h5>
        <div class="row">
          <div class="col-md-5">
            <div class="mb-3">
                <label for="inputNombre" class="form-label">*CLINICAS</label>
                <select name="clinica" id="setClinica"  onchange="changeConsultorio(null)" class="form-control">
                    <option value="">Seleccione una opción</option>
                    @foreach ($my_clinics as $my_clinic)
                    @php
                        $clinica = $my_clinic->clinica;
                    @endphp
                        <option value="{{ $clinica->idclinica }}">{{ $clinica->tnombre }}</option>
                    @endforeach
                </select>
            </div>
          </div>
          <div class="col-md-5">
            <div class="mb-3">
                <label for="inputApellido" class="form-label">*CONSULTORIOS</label>
                <select name="consultorio" id="setConsultorio" disabled  class="form-control">
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
          </div>
          <div class="col-md-2 text-end">
            <div class="mb-3">
              <label for="inputApellido" class="form-label">&nbsp;</label> <br>
                <button type="button" onclick="aplicarConsultorio()" class="btn btn-primary btn-block">Aplicar filtro</button>
            </div>
          </div>
        </div>
        <hr>
      </div>
        
    </div>
  </form>
</div>
@yield('content_header')
@yield('content')




<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/vendor/EasyAutocomplete/jquery.easy-autocomplete.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@livewireScripts

<script src="/js/app.js?version'.date('H-i-s')"></script>

<script>
  // Cerrar el menú al hacer clic en un enlace
  document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const navbarCollapse = document.getElementById('navbarNav');
      const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
        toggle: false
      });
      bsCollapse.hide();
    });
  });

  // Cerrar el menú al hacer clic fuera de él
  document.addEventListener('click', (event) => {
    const navbarCollapse = document.getElementById('navbarNav');
    const toggler = document.querySelector('.navbar-toggler');

    if (!navbarCollapse.contains(event.target) && !toggler.contains(event.target)) {
      const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
        toggle: false
      });
      bsCollapse.hide();
    }
  });
  
  
 
</script>

</body>
</html>

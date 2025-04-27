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
    <link rel="stylesheet"
        href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('clipjs')
    @livewireStyles

</head>

<body>
    @inject('ClinicaUser', 'App\Models\ClinicaUser')
    @inject('ConsultorioUser', 'App\Models\ConsultorioUser')

    @inject('User', 'App\Models\User')
    @inject('Package', 'App\Models\Package')
    @inject('Solicitud', 'App\Models\Solicitud')
    @php
        $user = $User::find(Auth::user()->id);
        $getPackage = $Package::find($user->idpackage);
    @endphp
    <style>
        #container {
            margin: 20px;
            width: 100px;
            height: 100px;
            position: relative;
        }
    </style>



    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">LOGO</a>

            <!-- Campana (visible solo en dispositivos pequeños) -->
            <a class="nav-link d-lg-none" href="#">
                <i class="fas fa-bell"></i>
            </a>

            <!-- Botón hamburguesa -->
            <button class="navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú desplegable -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Enlace Inicio -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Inicio</a>
                    </li>

                    <!-- Enlace Salir -->
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="nav-link" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Salir
                        </a>
                    </li>

                    <!-- Campana (visible solo en dispositivos grandes) -->
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
        $my_consultories = $ConsultorioUser::myConsultories();
        $solicitud = $Solicitud::getMyPackage();
    @endphp
    <div class="container bg-white py-2">
        <div class="row mt-3">
            <div class="col-12">
              <div class="border-0 h-100">
                  <div class="d-flex align-items-center justify-content-end">
                      <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3"
                          style="width: 55px; height: 55px">
                          <i class="fas fa-user-md text-white fs-3"></i>
                      </div>
                      
                      @if ($solicitud != null && $solicitud->isValidateCedula == 1)
                        <div class="flex-grow-0">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 me-2">{{ Auth::user()->name }}</h5>
                                <span>  </span> <span class="badge {{ $user->is_cedula_valid ? 'bg-success' : 'bg-danger' }}">{{ $user->is_cedula_valid ? 'Estatus: Activo' : 'Estatus: Inactivo' }}</span>
                            </div>
                            @if ($user->is_cedula_valid == false)
                                
                                <div class="text-muted small mt-1">
                                <a href="/admin/usuarios/activar/{{ $user->id }}" class="text-decoration-none">
                                    <i class="fas fa-user-check"></i>
                                    <span>Solicitar activación</span>
                                </a>
                                </div>
                            @endif
                           {{--  <div class="text-muted small">
                                {{ $getPackage != null ? $getPackage->nombre : 'No tienes paquete' }}</div>
                            </div> --}}
                      @else
                        <div class="flex-grow-0">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 me-2">{{ Auth::user()->name }}</h5>
                            </div>
                        </div>
                      @endif
                  </div>
              </div>
               
            </div>
        </div>

        @yield('filterByHome')
    </div>
    @yield('content_header')
    @yield('content')




    <script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/vendor/EasyAutocomplete/jquery.easy-autocomplete.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS (asegúrate de tenerlo en tu layout, aquí como respaldo) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script src="/js/app.js?version'.date('H-i-s')"></script>
    @yield('js')

    <script>
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            const navbarCollapse = document.getElementById('navbarNav');
            const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                toggle: false // Evita el comportamiento automático
            });

            if (navbarCollapse.classList.contains('show')) {
                bsCollapse.hide(); // Oculta si ya está visible
            } else {
                bsCollapse.show(); // Muestra si está oculto
            }
        });
    </script>

</body>

</html>

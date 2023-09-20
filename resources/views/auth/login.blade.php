@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center">
            <div class="col-7 text-center">
                <h5>INICIO DE SESION</h5>
            </div>
            <div class="col-12"> &nbsp; </div>
            <div class="col-4 col-md-3">
                <img class="img-fluid" src="{{ asset('image/img_index.jpg') }}" alt="">
            </div>
            <div class="col-8 col-md-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3 row">
                        <label for="staticCorreo" class="col-4 col-md-3 col-form-label">Correo</label>
                        <div class="col-8 col-md-9">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticContraseǹa" class="col-4 col-md-3 col-form-label">Contraseña</label>
                        <div class="col-8 col-md-9">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Recordar</label>
                        </div>
                    </div>

                    <div class="mb-0">
                        <button type="submit" class="btn btn-primary col-12">Ingresar</button>
                        @if (Route::has('password.request'))
                            <div class="col-12 text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">Recuperar contraseǹa</a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-7 mt-3">
                <p> <b>Importante:</b> </p>
                <ul>
                    <li>
                        Para poder ingresar al sistema debe proporcionar su nombre de usuario y clave y dar clic en el botón
                        "Ingresar".
                    </li>
                    <li>Si usted olvidó su clave, no se preocupe, solicítela dando clic en el enlace "Recuperar Contraseña".
                    </li>
                    <li>Sus datos de acceso son muy importantes pues con ellos ingresará al sistema, protégalos.</li>
                    <li>Le sugerimos cambiar su clave periódicamente.
                    </li>
                    <li>Utilice claves largas, difíciles de adivinar pero que pueda Usted memorizar y recordar, no las anote
                        en ninguna parte.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

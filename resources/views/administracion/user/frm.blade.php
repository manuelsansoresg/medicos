@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/usuarios">LISTA DE USUARIOS</a> </li>
                    <li class="breadcrumb-item">USUARIOS</li>
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

           <div class="col-12 mt-3">
               <form method="post" action="/admin/usuarios" id="frm-users">
              {{--  @if ($user_id == null)
               @else
               <form method="post" action="/admin/usuarios" id="upd-frm-users">
               @endif --}}
                   <div class="col-12">
                       <p class="text-info">Los campos marcados con * son requeridos</p>
                   </div>
                   <div class="col-12 py-3 mt-3">
                       <p class="lead">DATOS GENERALES</p>
                   </div>
                   @csrf
                   <div class="row">
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputNombre" class="form-label">*NOMBRE(S)</label>
                               <input type="text" class="form-control" name="data[name]" id="inputNombre" value="{{ $user != null ? $user->name : null }}" required>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputApellido" class="form-label">*APELLIDO PATERNO</label>
                               <input type="text" class="form-control" name="data[vapellido]" id="inputApellido" value="{{ $user != null ? $user->vapellido : null }}" required>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputApellido" class="form-label">*APELLIDO MATERNO</label>
                               <input type="text" class="form-control" name="data[segundo_apellido]" id="inputApellido" value="{{ $user != null ? $user->segundo_apellido : null }}" required>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputTelefono" class="form-label">TELEFONO</label>
                               <input type="text" class="form-control" name="data[ttelefono]" id="inputTelefono" value="{{ $user != null ? $user->ttelefono : null }}">
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputDireccion" class="form-label">DIRECCIÓN</label>
                               <textarea name="data[tdireccion]" id="inputDireccion" cols="30" rows="5" class="form-control">{{ $user != null ? $user->tdireccion : null }}</textarea>
                           </div>
                       </div>
                       @hasrole(['administrador', 'medico', 'auxiliar'])
                       <div class="col-md-6">
                          
                           <div class="mb-3">
                               <label for="inputPuesto" class="form-label">*PUESTO</label>
                               @php
                               $rol = $user!= null ?  $user->getRoleNames()[0] : null;
                           @endphp
                               <select name="rol" id="inputPuesto" class="form-control" required onchange="addPrincipalUser(this)">
                                   @foreach ($puestos as $key =>  $puesto)
                                       <option value="{{ $key}}" {{ $user != null && $rol === $key ? 'selected' : null }}> {{ $puesto}} </option>
                                   @endforeach
                               </select>
                           </div>
                       </div>
       
                       <div class="col-md-6" id="content-financial_product_id">
                           <div class="form-group">
                               <label class="form-label">CLINICAS</label>
                               <div class="form-control-wrap">
                                   <select name="clinicas[]" id="" class="form-control select2multiple" multiple="multiple"  data-search="on">
                                      @foreach ($clinicas as $clinica)
                                          <option value="{{ $clinica->idclinica }}" 
                                           @foreach ($my_clinics as $my_clinic)
                                             {{ $my_clinic->clinica_id == $clinica->idclinica ? 'selected' : null}}
                                           @endforeach
                                           >{{ $clinica->tnombre }}</option>
                                      @endforeach
                                   </select>
                               </div>
                           </div>
                       </div>
                       
                       <div class="col-md-6" id="content-financial_product_id">
                           <div class="form-group">
                               <label class="form-label">CONSULTORIOS</label>
                               <div class="form-control-wrap">
                                   <select name="consultorios[]" id="" class="form-control select2multiple" multiple="multiple"  data-search="on">
                                      @foreach ($consultorios as $consultorio)
                                          <option value="{{ $consultorio->idconsultorios }}" 
                                           @foreach ($my_consultorios as $my_consultorio)
                                             {{ $my_consultorio->consultorio_id == $consultorio->idconsultorios ? 'selected' : null}}
                                           @endforeach
                                           >{{ $consultorio->vnumconsultorio }}</option>
                                      @endforeach
                                   </select>
                               </div>
                           </div>
                       </div>
       
                     
                       @endhasrole
                       @hasrole('administrador')
                       <div class="col-md-6" id="usuario_propietario">
                           <div class="form-group">
                               <label for="InputDoctor">USUARIO PROPIETARIO</label>
                               <select name="data[usuario_principal]" id="usuario_principal" class="form-control select2multiple">
                                   @foreach ($userAdmins as $userAdmin)
                                       <option value="{{ $userAdmin->id }}" {{ $user != null && $userAdmin->id == $user->usuario_principal ? 'selected' : null }}>{{ $userAdmin->name }}</option>
                                   @endforeach
                               </select>
                               <small id="doctorHelp" class="form-text text-muted">Usuario propietario para aplicar filtros de busqueda.</small>
                           </div>
                       </div>
                       @endrole
    
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputEstatus" class="form-label">*ACTIVO</label>
                               <select name="data[status]" id="inputEstatus" class="form-control" required>
                                   
                                  @foreach (config('enums.status') as $key => $item)
                                      <option value="{{ $key }}" {{ $user != null && $user->status == $key ? 'selected' : null  }}>{{ $item }}</option>
                                  @endforeach
                               </select>
                           </div>
                       </div>
                       <div class="col-12 py-3 mt-3">
                           <p class="lead">DATOS PARA INGRESAR AL SISTEMA</p>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputCorreo" class="form-label">*CORREO</label>
                               <input type="email" class="form-control" name="data[email]" id="inputCorreo" value="{{ $user != null ? $user->email : null }}" required>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputPassword" class="form-label">{{ $user_id == null ? '*' : null}}CONTRASEÑA</label>
                               <input type="password" class="form-control" name="password" id="inputPassword" {{ $user_id == null ? 'required' : '' }}>
                              
                           </div>
                       </div>
                       <div class="col-12 py-3 mt-3">
                           <p class="lead">INFORMACIÓN COMPLEMENTARIA</p>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputCedula" class="form-label">CÉDULA PROFESIONAL</label>
                               <input type="text" class="form-control" name="data[vcedula]" id="inputCedula" value="{{ $user != null ? $user->vcedula : null }}">
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputRfc" class="form-label">RFC</label>
                               <input type="text" class="form-control" name="data[RFC]" id="inputRfc" value="{{ $user != null ? $user->RFC : null }}">
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="inputEspecialidad" class="form-label">ESPECIALIDAD O TÍTULO</label>
                               <input type="text" class="form-control" name="data[especialidad]" id="inputEspecialidad" value="{{ $user != null ? $user->especialidad : null }}">
                           </div>
                       </div>
                       <div class="col-md-12 text-end">
                           <div class="mb-3">
                               <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}" >
                               <button class="btn btn-primary">Guardar</button>
                           </div>
                       </div>
                   </div>
                   
               </form>
           </div>
       </div>
    
    </div>
</div>


@endsection
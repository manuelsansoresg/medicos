@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item"> <a href="/admin/template-formulario">PLANTILLA CONSULTA</a></li>
                        <li class="breadcrumb-item active">PLANTILLA</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@stop

@section('content')

<div class="container bg-white py-2">
    <div class="row mt-3">
        <div class="col-12">
            <form action="{{ route('template-formulario.store') }}" method="POST">
                @csrf
                <label for="name">Nombre de la Configuración</label>
                <input type="text" class="form-control" name="name" required>

                    @hasrole('administrador')
                        <div class="form-group">
                            <div class="col-12">
                                <label for="InputDoctor">DOCTOR</label>
                            </div>
                            <div class="col-12">
                                <select name="user_id" id="user_id" class="form-control select2multiple">
                                    @foreach ($userAdmins as $userAdmin)
                                        <option value="{{ $userAdmin->id }}">{{ $userAdmin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="doctorHelp" class="form-text text-muted">Doctor que se le asignara el template.</small>
                        </div> 
                        @else
                        <input type="hidden" name="user_id" id="iddoctor" value="{{ Auth::user()->id }}">
                    @endrole
                
                <div id="fields">
                    <div class="field">
                        <label for="fields[0][name]">Nombre del Campo</label>
                        <input type="text" class="form-control" name="fields[0][name]" required>
        
                        <label for="fields[0][type]">Tipo de Campo</label>
                        <select class="form-control field-type" name="fields[0][type]" required>
                            <option value="text">Texto</option>
                            <option value="date">Fecha</option>
                            <option value="textarea">Área de Texto</option>
                            <option value="select">Seleccionar</option>
                            {{-- <option value="image">Imagen</option> --}}
                        </select>
        
                        <label for="fields[0][is_required]">¿Es Obligatorio?</label>
                        <input type="checkbox" name="fields[0][is_required]">
        
                        <div class="col-12 field-options" style="display:none;">
                            <label for="fields[0][options]">Opciones</label>
                            <textarea class="form-control" name="fields[0][options]"></textarea>
                        </div>
                    </div>
                </div>
        
                <div class="col-12 mt-3">
                    <button type="button" class="btn btn-success" id="add-field">Añadir Campo</button>
                    <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                </div>
                
            </form>
        </div>
    </div>
</div>
    

   
@endsection
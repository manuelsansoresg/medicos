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
            <form action="{{ route('template-formulario.update', $configuration->id) }}" method="POST">
                @csrf
                @method('PUT')
                <label for="name">Nombre de la Configuración</label>
                <input type="text" class="form-control" name="name" value="{{ $configuration->name }}" required>
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
                    @foreach($configuration->fields as $index => $field)
                        <div class="field py-2">
                            <label for="fields[{{ $index }}][name]">Nombre del Campo</label>
                            <input  class="form-control" type="text" name="fields[{{ $index }}][name]" value="{{ $field->field_name }}" required>
            
                            <label for="fields[{{ $index }}][type]">Tipo de Campo</label>
                            <select class="form-control field-type" name="fields[{{ $index }}][type]" required>
                                <option value="text" @if($field->field_type == 'text') selected @endif>Texto</option>
                                <option value="date" @if($field->field_type == 'date') selected @endif>Fecha</option>
                                <option value="textarea" @if($field->field_type == 'textarea') selected @endif>Área de Texto</option>
                                <option value="select" @if($field->field_type == 'select') selected @endif>Seleccionar</option>
                                {{-- <option value="image" @if($field->field_type == 'image') selected @endif>Imagen</option> --}}
                            </select>
            
                            <label for="fields[{{ $index }}][is_required]">¿Es Obligatorio?</label>
                            <input type="checkbox" name="fields[{{ $index }}][is_required]" @if($field->is_required) checked @endif>

                            <div class="col-12 field-options" style="display:none;">
                                <label for="fields[{{ $index }}][options]">Opciones</label>
                                <textarea class="form-control" name="fields[{{ $index }}][options]">{{ $field->options }}</textarea>
                            </div>

                        </div>
                    @endforeach
                </div>
            
               <div class="col-12 mt-3">
                <button type="button" id="add-field" class="btn btn-success">Añadir Campo</button>
                <button type="submit" class="btn btn-primary">Guardar Configuración</button>
               </div>
            </form>
        </div>
    </div>
</div>




@endsection
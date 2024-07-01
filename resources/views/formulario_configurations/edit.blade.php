@extends('adminlte::page')

@section('content')
<h1>Editar Configuración de Formulario</h1>
<form action="{{ route('formulario_configurations.update', $configuration->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Nombre de la Configuración</label>
    <input type="text" name="name" value="{{ $configuration->name }}" required>
    
    <div id="fields">
        @foreach($configuration->fields as $index => $field)
            <div class="field">
                <label for="fields[{{ $index }}][name]">Nombre del Campo</label>
                <input type="text" name="fields[{{ $index }}][name]" value="{{ $field->field_name }}" required>

                <label for="fields[{{ $index }}][type]">Tipo de Campo</label>
                <select name="fields[{{ $index }}][type]" required>
                    <option value="text" @if($field->field_type == 'text') selected @endif>Texto</option>
                    <option value="date" @if($field->field_type == 'date') selected @endif>Fecha</option>
                    <option value="textarea" @if($field->field_type == 'textarea') selected @endif>Área de Texto</option>
                    <option value="select" @if($field->field_type == 'select') selected @endif>Seleccionar</option>
                    <option value="image" @if($field->field_type == 'image') selected @endif>Imagen</option>
                </select>

                <label for="fields[{{ $index }}][is_required]">¿Es Obligatorio?</label>
                <input type="checkbox" name="fields[{{ $index }}][is_required]" @if($field->is_required) checked @endif>

                <label for="fields[{{ $index }}][options]">Opciones (solo para Select)</label>
                <textarea name="fields[{{ $index }}][options]">{{ $field->options }}</textarea>
            </div>
        @endforeach
    </div>

    <button type="button" id="add-field">Añadir Campo</button>
    <button type="submit">Guardar Configuración</button>
</form>

<script>
    document.getElementById('add-field').addEventListener('click', function () {
        const fields = document.getElementById('fields');
        const fieldCount = fields.getElementsByClassName('field').length;

        const newField = document.createElement('div');
        newField.classList.add('field');
        newField.innerHTML = `
            <label for="fields[${fieldCount}][name]">Nombre del Campo</label>
            <input type="text" name="fields[${fieldCount}][name]" required>

            <label for="fields[${fieldCount}][type]">Tipo de Campo</label>
            <select name="fields[${fieldCount}][type]" required>
                <option value="text">Texto</option>
                <option value="date">Fecha</option>
                <option value="textarea">Área de Texto</option>
                <option value="select">Seleccionar</option>
                <option value="image">Imagen</option>
            </select>

            <label for="fields[${fieldCount}][is_required]">¿Es Obligatorio?</label>
            <input type="checkbox" name="fields[${fieldCount}][is_required]">

            <label for="fields[${fieldCount}][options]">Opciones (solo para Select)</label>
            <textarea name="fields[${fieldCount}][options]"></textarea>
        `;
        fields.appendChild(newField);
    });
</script>
@endsection
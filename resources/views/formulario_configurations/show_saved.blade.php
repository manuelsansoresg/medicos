@extends('adminlte::page')

@section('content')
<h1>Valores guardados</h1>
<form action="{{ route('formulario_entries.update', $entry->id) }}" method="POST">
    @csrf
    @method('PUT')
    @foreach($entry->fields as $entryField)
        @php
            $field = $entryField->field;
        @endphp
        <div class="field">
            <label for="{{ $field->field_name }}">{{ $field->field_name }}</label>
            @if($field->field_type == 'text')
                <input type="text" name="fields[{{ $field->id }}]" value="{{ $entryField->value }}">
            @elseif($field->field_type == 'date')
                <input type="date" name="fields[{{ $field->id }}]" value="{{ $entryField->value }}">
            @elseif($field->field_type == 'textarea')
                <textarea name="fields[{{ $field->id }}]">{{ $entryField->value }}</textarea>
            @elseif($field->field_type == 'select')
                <select name="fields[{{ $field->id }}]">
                    @foreach(explode(',', $field->options) as $option)
                        <option value="{{ $option }}" @if($entryField->value == $option) selected @endif>{{ $option }}</option>
                    @endforeach
                </select>
            @elseif($field->field_type == 'image')
                <!-- Aquí podrías mostrar una imagen previa si el valor es una URL de imagen -->
                <input type="file" name="fields[{{ $field->id }}]">
                @if(filter_var($entryField->value, FILTER_VALIDATE_URL) !== false)
                    <img src="{{ $entryField->value }}" alt="Imagen">
                @else
                    <p>No se ha proporcionado una imagen.</p>
                @endif
            @endif
        </div>
    @endforeach
    <button type="submit">Guardar cambios</button>
</form>
@endsection
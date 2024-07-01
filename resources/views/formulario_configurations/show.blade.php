@extends('adminlte::page')
@section('content')
    <h1>{{ $configuration->name }}</h1>
    <form action="{{ route('formularios.store', ['id' => $configuration->id, 'consultaId' => $consultaId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @foreach($configuration->fields as $field)
            <div>
                <label for="field_{{ $field->id }}">{{ $field->field_name }}</label>
                @if($field->field_type == 'text')
                    <input type="text" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @elseif($field->field_type == 'date')
                    <input type="date" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @elseif($field->field_type == 'textarea')
                    <textarea name="field_{{ $field->id }}" @if($field->is_required) required @endif></textarea>
                @elseif($field->field_type == 'select')
                    <select name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                        @foreach(explode(',', $field->options) as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @elseif($field->field_type == 'image')
                    <input type="file" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @endif
            </div>
        @endforeach
        <button type="submit">Guardar</button>
    </form>
@endsection

@extends('adminlte::page')

@section('content')
    <h1>Configuraciones de Formularios</h1>
    <a href="{{ route('formulario_configurations.create') }}">Crear Nueva Configuración</a>
    <ul>
        @foreach($configurations as $configuration)
            <li>
                {{ $configuration->name }}
                <a href="{{ route('formulario_configurations.edit', $configuration->id) }}">Editar</a>
                <form action="{{ route('formulario_configurations.destroy', $configuration->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
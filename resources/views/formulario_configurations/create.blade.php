@extends('adminlte::page')

@section('content')
    <h1>Crear Configuración de Formulario</h1>
    <form action="{{ route('formulario_configurations.store') }}" method="POST">
        @csrf
        <label for="name">Nombre de la Configuración</label>
        <input type="text" name="name" required>
        
        <div id="fields">
            <div class="field">
                <label for="fields[0][name]">Nombre del Campo</label>
                <input type="text" name="fields[0][name]" required>

                <label for="fields[0][type]">Tipo de Campo</label>
                <select name="fields[0][type]" required>
                    <option value="text">Texto</option>
                    <option value="date">Fecha</option>
                    <option value="textarea">Área de Texto</option>
                    <option value="select">Seleccionar</option>
                    <option value="image">Imagen</option>
                </select>

                <label for="fields[0][is_required]">¿Es Obligatorio?</label>
                <input type="checkbox" name="fields[0][is_required]">

                <label for="fields[0][options]">Opciones (solo para Select)</label>
                <textarea name="fields[0][options]"></textarea>
            </div>
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
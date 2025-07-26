@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item"> <a href="/admin/paquete">LISTA DE PAQUETES</a> </li>
                    <li class="breadcrumb-item">PAQUETE</li>
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
                <form id="frm-paquete">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <p class="text-info">Los campos marcados con * son requeridos</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">*NOMBRE</label>
                                <input type="text" class="form-control" name="data[nombre]" id="inputNombre" value="{{ $query != null ? $query->nombre : null }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputPrecio" class="form-label">*PRECIO</label>
                                <input type="number" name="data[precio]" id="inputPrecio" class="form-control"
                                       value="{{ $query != null ? ($query->precio) : null }}"
                                       min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputPrecio" class="form-label">*Elementos</label>
                                @foreach ($elementos as $key => $elemento)
                                    <div class="form-check mb-2">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md-4">
                                                <input class="form-check-input" 
                                                    name="elementos[]"  
                                                    type="checkbox" 
                                                    value="{{ $elemento->id }}" 
                                                    id="check-{{ $key }}"
                                                    {{ $elementosGuardados != null &&  in_array($elemento->id, $elementosGuardados) ? 'checked' : '' }}
                                                    onclick="toggleMaxField(this, 'max-{{ $elemento->id }}')">
                                                <label class="form-check-label" for="check-{{ $key }}">
                                                    {{ $elemento->nombre }}
                                                </label>
                                            </div>
                                            <div class="col-6 col-md-5">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Máximo:</span>
                                                    </div>
                                                    <input type="number" 
                                                        id="max-{{ $elemento->id }}"
                                                        class="form-control form-control-sm" 
                                                        name="max[{{ $elemento->id }}]" 
                                                        placeholder="Valor" 
                                                        min="0"
                                                        value="{{ isset($elementosMaximos[$elemento->id]) ? $elementosMaximos[$elemento->id] : '' }}"
                                                        {{ $elementosGuardados != null &&  !in_array($elemento->id, $elementosGuardados) ? 'disabled' : '' }}>
                                                </div>
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Precio:</span>
                                                        
                                                    </div>
                                                    <input type="number" 
                                                        id="precio-{{ $elemento->id }}"
                                                        class="form-control form-control-sm" 
                                                        disabled
                                                        placeholder="Precio" 
                                                        min="0"
                                                        step="0.01"
                                                        value="{{ $elemento->precio }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mt-3 col-12 col-md-9 text-end">
                                    <strong>Total: ${{ $elementos->sum('precio') }}</strong>
                                    <br>
                                    
                                </div>
                                <div class="col-12">
                                    <small>Este texto informativo es la suma de los precios individualmente de los elementos del paquete </small>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                        function toggleMaxField(checkbox, maxFieldId) {
                            const maxField = document.getElementById(maxFieldId);
                            maxField.disabled = !checkbox.checked;
                            if (!checkbox.checked) {
                                maxField.value = '';
                            }
                        }
                        
                        // Inicializar los campos al cargar la página
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkboxes = document.querySelectorAll('input[name="elementos[]"]');
                            checkboxes.forEach(function(checkbox) {
                                const elementoId = checkbox.value;
                                const maxField = document.getElementById('max-' + elementoId);
                                if (maxField) {
                                    maxField.disabled = !checkbox.checked;
                                }
                            });
                        });
                        </script>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputValidateCedula" class="form-label">*VALIDAR CÉDULA</label>
                                <select name="data[isValidateCedula]" id="inputValidateCedula" class="form-control" required>
                                    <option value="1"{{ $query != null &&  $query->isValidateCedula == 1 ? 'selected' : null }}>SÍ</option>
                                    <option value="0"{{ $query != null &&  $query->isValidateCedula == 0 ? 'selected' : null }}>NO</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputOwnerType" class="form-label">Tipo </label>
                                <select name="data[owner_type]" id="inputOwnerType" class="form-control" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="clinica" {{ $query != null && $query->owner_type == 'clinica' ? 'selected' : null }}>Clínica</option>
                                    <option value="consultorio" {{ $query != null && $query->owner_type == 'consultorio' ? 'selected' : null }}>Consultorio</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputEstatus" class="form-label">*ACTIVO</label>
                                <select name="data[status]" id="inputEstatus" class="form-control" required>

                                    @foreach (config('enums.status') as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $query != null && $query->status == $key ? 'selected' : null }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputReporte" class="form-label">SOPORTE</label>
                                <select name="data[tipoReporte]" id="inputReporte" class="form-control">

                                    @foreach (config('enums.soporte') as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $query != null && $query->tipoReporte == $key ? 'selected' : null }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        
                        
                      
                       
                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <input type="hidden" name="id" value="{{ $id }}" >
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
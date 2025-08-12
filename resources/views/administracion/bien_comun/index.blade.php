@extends('layouts.template')

@section('content_header')
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                        <li class="breadcrumb-item">BIEN COMÚN</li>
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
                <div class="col-12 text-end mb-3">
                    <a href="/" class="btn btn-primary"><i class="fas fa-home"></i></a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBienComun">
                        <i class="fas fa-plus"></i> 
                    </button>
                </div>

                <div class="col-12 mt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="filtroMes" class="form-label">Filtrar por mes:</label>
                        <select id="filtroMes" class="form-select">
                            <option value="">Todos los meses</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroUsuario" class="form-label">Filtrar por usuario:</label>
                        <select id="filtroUsuario" class="form-select">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Vista de Calendario -->
                <div class="row">
                    <div class="col-12">
                        <div id="calendar-container" class="calendar-view">
                            @php
                                $groupedByDate = $bienComunes->groupBy(function($item) {
                                    return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                                });
                            @endphp

                            @if($groupedByDate->count() > 0)
                                @foreach($groupedByDate as $date => $items)
                                    <div class="calendar-day-card mb-4" data-date="{{ $date }}">
                                        <div class="calendar-day-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-calendar-day me-2"></i>
                                                {{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                            </h5>
                                        </div>
                                        <div class="calendar-day-content">
                                            @foreach($items->sortBy('hour') as $item)
                                                <div class="bien-comun-item" data-user-id="{{ $item->user_id }}" data-month="{{ \Carbon\Carbon::parse($item->date)->month }}">
                                                    <div class="bien-comun-card">
                                                        <div class="bien-comun-header">
                                                            <div class="bien-comun-time">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ \Carbon\Carbon::parse($item->hour)->format('g:i A') }}
                                                            </div>
                                                            <div class="bien-comun-actions">
                                                                <button class="btn btn-sm btn-primary me-1" onclick="editBienComun({{ $item->id }}, '{{ $item->name }}', '{{ $item->user_id }}', '{{ $item->date }}', '{{ $item->hour }}', '{{ addslashes($item->description) }}')">
                                                                    <i class="fas fa-edit text-white"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger" onclick="deleteBienComun({{ $item->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="bien-comun-body">
                                                            <h6 class="bien-comun-title">{{ $item->name }}</h6>
                                                            <p class="bien-comun-user">
                                                                <i class="fas fa-user me-1"></i>
                                                                {{ $item->user->name ?? 'Usuario no encontrado' }}
                                                            </p>
                                                            @if($item->description)
                                                                <p class="bien-comun-description">{{ $item->description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay registros de bien común</h5>
                                    <p class="text-muted">Agrega el primer registro haciendo clic en el botón "+"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar Bien Común -->
    <div class="modal fade" id="modalBienComun" tabindex="-1" aria-labelledby="modalBienComunLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBienComunLabel">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Bien Común
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formBienComun">
                    <input type="hidden" id="bien_comun_id" name="bien_comun_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Usuario *</label>
                                    <select class="form-select" id="user_id" name="user_id" required>
                                        <option value="">Seleccionar usuario</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hour" class="form-label">Hora *</label>
                                    <input type="time" class="form-control" id="hour" name="hour" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Descripción opcional del bien común"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/biencomun.js') }}"></script>
@endsection
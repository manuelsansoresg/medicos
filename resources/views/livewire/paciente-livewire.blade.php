<div class="container">
    <form id="frm-download-expedient">
        <div class="row justify-content-end">
            <div class="col-6 float-right py-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por nombre o código" wire:model="search">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
       <div class="col-12 text-end py-3">
            @if ($isDownload === true && $isShowDownload === true)
                <button class="btn btn-primary disabled" id="btn-download-expedient">DESCARGAR EXPEDIENTES SELECCIONADOS</button>
            @endif
       </div>
    
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        @if ($isDownload == true && $isOriginSolicitud == false)
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAll" value="1">
                                </div>
                            </td>
                        @endif
                        <td>NOMBRE</td>
                        <td>CÓDIGO PACIENTE</td>
                        <td>ACCIONES</td>
                    </tr>
                    @foreach ($pacientes as $paciente)
                        <tr>
                            @if ($isDownload == true && $isOriginSolicitud == false)
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input selectExpedient" name="expedients[]" value="{{ $paciente->id }}">
                                    </div>
                                </td>
                            @endif
                            <td> {{ $paciente->id }} -  {{ $paciente->name }} {{ $paciente->vapellido }} </td>
                            <td> {{ $paciente->codigo_paciente }}</td>
                            <td> 
                                @php
                                    $nombre = $paciente->name.' '. $paciente->vapellido;
                                @endphp
                                @if ($isOriginSolicitud == false)
                                    <a href="/admin/expedientes/{{ $paciente->id }}" class="btn btn-primary"><i class="far fa-folder-open"></i></a>
                                    @else
                                    <a href="#" id="btn-{{ $paciente->id }}" class="btn btn-primary btn-toggle" onclick="toggleSelectionWithLimit('{{ $paciente->id }}')">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="col-12 text-center py-3">
            <div wire:loading class="loading">
                <i class="fas fa-spinner fa-3x fa-spin"></i>
            </div>
        </div>
        <div class="col-12">
    
            {{ $pacientes->links() }}
        </div>
    </form>
</div>

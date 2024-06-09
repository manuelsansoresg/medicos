<div class="container">
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
   

    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td>NOMBRE</td>
                    <td>CÓDIGO PACIENTE</td>
                    <td>ACCIONES</td>
                </tr>
                @foreach ($pacientes as $paciente)
                    <tr>
                        <td> {{ $paciente->vnombre }} {{ $paciente->vapellido }} </td>
                        <td> {{ $paciente->codigo_paciente }}</td>
                        <td> 
                            @php
                                $nombre = $paciente->name.' '. $paciente->vapellido;
                            @endphp
                            <a href="/admin/expedientes/{{ $paciente->id }}" class="btn btn-primary"><i class="far fa-folder-open"></i></a>
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
</div>

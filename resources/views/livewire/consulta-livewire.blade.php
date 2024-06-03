
<div >
    <div class="row justify-content-end">
        <div class="col-6 float-right py-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar por fecha o motivo" wire:model="search">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th>PESO</th>
                    <th>MOTIVO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultas as $consulta)
                    <tr>
                        <td>{{ Carbon\Carbon::parse($consulta->created_at)->format('Y-m-d h:i a') }}</td>
    
                        <td>{{ $consulta->peso }}</td>
                        <td>{{ $consulta->motivo }}</td>
                        <td>

                            <a  href="/admin/consulta/{{ $consulta->id }}/generate/pdf" target="_blank" class="btn btn-secondary pointer"><i class="fas fa-print"></i></a>
                            <a  onclick="editarConsulta({{ $consulta->id }})" class="btn btn-primary pointer"><i class="fas fa-edit"></i></a>
                            <a  onclick="deleteConsulta({{ $consulta->id }})" class="btn btn-danger pointer"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12 text-center py-3">
        <div wire:loading class="loading">
            <i class="fas fa-spinner fa-3x fa-spin"></i>
        </div>
    </div>
    <div class="col-12">

        {{ $consultas->links() }}
    </div>
</div>
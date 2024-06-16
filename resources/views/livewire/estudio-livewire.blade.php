<div>
    @if ($selectedTab == 'estudios')
   
        <div class="row justify-content-end">
            <div class="col-6 float-right py-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por estudio o diagnostico"
                        wire:model="search">
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
                        <th>ESTUDIOS</th>
                        <th>DIAGNOSTICOS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($consultas as $consulta)
                        <tr>
                            <td>{{ Carbon\Carbon::parse($consulta->created_at)->format('Y-m-d h:i a') }}</td>

                            <td>{{ $consulta->estudios }}</td>
                            <td>{{ $consulta->diagnosticos }}</td>
                            <td>

                                <a href="/admin/estudio/{{ $consulta->id }}/generate/pdf" target="_blank"
                                    class="btn btn-secondary pointer"><i class="fas fa-print"></i></a>
                                    @if (!$isExpedient)
                                        <a onclick="editarEstudio({{ $consulta->id }})" class="btn btn-primary pointer"><i
                                                class="fas fa-edit"></i></a>
                                        <a onclick="deleteEstudio({{ $consulta->id }})" class="btn btn-danger pointer"><i
                                                class="fas fa-trash"></i></a>
                                    @endif
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
    @endif
</div>

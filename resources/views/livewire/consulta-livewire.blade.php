
<div>
    @inject('formularioEntryField', 'App\Models\FormularioEntryField')
    @inject('Mconsulta', 'App\Models\Consulta')

    <div class="row justify-content-end">
        <div class="col-6 float-right py-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar" wire:model="search">
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
                    <th></th>
                    <th></th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultas as $consulta)
                    @php
                        $fields = $formularioEntryField::getFields($consulta->id);
                        $consultaPdf = $Mconsulta::getPdf($consulta->paciente_id);
                    @endphp
                   
                   <tr>
                    <td>{{ Carbon\Carbon::parse($consulta->created_at)->format('Y-m-d h:i a') }}</td>

                    <td>
                        <b>{{ isset($fields[0])? $fields[0]['name']: null }} : </b> {{ isset($fields[0])? $fields[0]['value']: null }} 
                    </td>
                    <td>
                        @if (isset($fields[1]))
                        <b>
                            {{ isset($fields[1])? $fields[1]['name']: null }} : </b> {{ isset($fields[1])? $fields[1]['value']: null }}
                        @endif
                      
                    </td>
                    <td>
                        {{-- <a  href="/admin/consulta/{{ $consulta->id }}/consulta/generate/pdf" target="_blank" class="btn btn-secondary pointer"><i class="far fa-folder-open"></i></a> --}}
                        <a  href="{{ $consultaPdf }}" target="_blank" class="btn btn-secondary pointer"><i class="fas fa-print"></i></a>
                        @if (!$isExpedient)
                            
                            <a  onclick="editarConsulta({{ $consulta->id }})" class="btn btn-primary pointer"><i class="fas fa-edit"></i></a>
                            <a  onclick="deleteConsulta({{ $consulta->id }})" class="btn btn-danger pointer"><i class="fas fa-trash"></i></a>
                            @else
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
</div>
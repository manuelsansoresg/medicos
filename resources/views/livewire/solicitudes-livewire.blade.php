<div class="row mt-3">
    @inject('MSolicitud', 'App\Models\Solicitud')
    <div class="col-12">
        
        <div class="row mt-3 pb-5">
            <div class="col-12">
                <div class="col-12 text-center">
                    <p class="h6">SOLICITUDES <a href="/admin/solicitudes/create" class="color-primary"><i class="fas fa-plus"></i></a></p>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label for="fechaInicio" class="col-auto col-form-label">FECHA INICIAL</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" placeholder="Fecha inicio" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label for="fechaFinal" class="col-auto col-form-label">FECHA FINAL</label>
                            <input type="date" class="form-control" id="fechaFinal" name="fechaFinal" placeholder="Fecha final" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label  class="col-auto col-form-label">NOMBRE</label>
                            <input type="text" class="form-control" placeholder="Buscar por nombre" wire:model="search">
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Filtrar</button> &nbsp;
                        <a href="/comision/lista/show" class="btn btn-primary ml-3">Limpiar Filtro</a>
                    </div>
                </div>
            </div>
        </div>
        
        {{ $search }}
        
        <table class="table">
            <tr>
                <th>NOMBRE</th>
                @hasrole(['administrador'])
                <th>USUARIO</th>
                @endrole
                <th>FECHA SOLICITUD</th>
                <th>CANTIDAD</th>
                <th>VENCIMIENTO</th>
                <th>ESTATUS</th>
                <th>ACCIONES</th>
            </tr>
           @foreach ($solicitudes as $solicitud)
                @php
                    $mesesRestantes = null;
                    $fechaVencimiento = $solicitud->fecha_vencimiento;
                    $isVencido = false;
                    if ($solicitud->catalog_prices_id == 1) {
                        $mesesRestantes = $MSolicitud::getPaqueteActivo($solicitud)['mesesRestantes'];
                    }
                @endphp
               <tr>
                <td>{{ $solicitud->nombre }}</td>
                @hasrole(['administrador'])
                <td> {{ $solicitud->name }} {{ $solicitud->apellido }}  </td>
                @endrole
                <td>{{ date('d-m-Y', strtotime($solicitud->created_at)) }}</td>
                <td>{{ $solicitud->cantidad }}</td>
                <td>
                    @php
                        
                        $color = 'color-primary';
                        if ($mesesRestantes >= 10) {
                            $color = 'text-warning';
                        }
                        
                        if ($mesesRestantes === 0) {
                            $color = 'text-danger';
                            $isVencido = true;
                            $MSolicitud::where(['id' => $solicitud->id, 'estatus' => 1])->update([
                                'estatus' => 2
                            ]);
                        }
                    @endphp
                   {{--  {{ $fechaVencimiento }} --}}
                    <span class="{{ $color }}">{{ $fechaVencimiento }}</span>
                </td>
                <td>
                    @switch($solicitud->estatus)
                        @case(1)
                            ACTIVO
                            @break
                        @case(2)
                            CADUCADO
                            @break
                        @default
                            PENDIENTE
                    @endswitch
                </td>
                <td>
                    <a href="/admin/solicitudes/{{ $solicitud->id }}"  class="color-primary"><i class="fas fa-eye"></i></a> 
                    @if ($isVencido === true)
                        <a class="color-primary pointer" onclick="renew({{ $solicitud->id }})"><i class="fas fa-redo"></i></a>
                    @endif
                </td>
               </tr>
           @endforeach
        </table>

        <div class="d-flex justify-content-center">
            {{ $solicitudes->links() }}
        </div>
    </div>
</div>

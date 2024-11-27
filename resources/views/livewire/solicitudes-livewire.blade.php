<div class="row mt-3">
    @inject('MSolicitud', 'App\Models\Solicitud')
    <div class="col-12">
        
        <div class="row mt-3 pb-5">
            <div class="col-12">
                <div class="col-12 text-center">
                    <p class="h6">SOLICITUDES <a href="/admin/solicitudes/create" class="color-primary"><i class="fas fa-plus"></i></a></p>
                </div>
                <div class="row mt-3 justify-content-end">
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label  class="col-auto col-form-label">NOMBRE SOLICITUD</label>
                            <select class="form-control"  wire:model="solicitud_origin_id">
                                <option value="">Todos</option>
                                @foreach ($catalogoSolicitudes as $catalogoSolicitud)
                                    <option value="{{ $catalogoSolicitud->id }}">{{ $catalogoSolicitud->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @hasrole(['administrador'])
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label  class="col-auto col-form-label">NOMBRE</label>
                            <input type="text" class="form-control" placeholder="Buscar por nombre" wire:model="search">
                        </div>
                    </div>
                    @endrole
                    {{-- <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Filtrar</button> &nbsp;
                        <a href="/comision/lista/show" class="btn btn-primary ml-3">Limpiar Filtro</a>
                    </div> --}}
                </div>
            </div>
        </div>
        
        <div class="table-responsive">

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
                            
                            if ($solicitud->estatus == 2) {
                                $color = 'text-danger';
                                $isVencido = true;
                               /*  $MSolicitud::where(['id' => $solicitud->id, 'estatus' => 1])->update([
                                    'estatus' => 2
                                ]); */
                            }
                        @endphp
                       {{--  {{ $fechaVencimiento }} --}}
                        <span class="{{ $color }}">{{ $fechaVencimiento }}  </span>
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
                        @if ($isVencido === true && $solicitud->catalog_prices_id != 3 && $solicitud->catalog_prices_id != 2)
                            <a class="color-primary pointer" onclick="renew({{ $solicitud->id }})"><i class="fas fa-redo"></i></a>
                        @endif
                        @if ($isVencido === true && ($solicitud->catalog_prices_id == 3 || $solicitud->catalog_prices_id == 2))
                            <a class="color-primary pointer" onclick="modalRenewSolicitud({{ $solicitud->id }})"><i class="fas fa-redo"></i></a>
                        @endif
                    </td>
                   </tr>
               @endforeach
            </table>
        </div>
        

        <div class="d-flex justify-content-center">
            {{ $solicitudes->links() }}
        </div>
    </div>
</div>

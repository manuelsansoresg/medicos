<div class="row mt-3">
    @inject('MSolicitudPaciente', 'App\Models\SolicitudPaciente') 
    <div class="col-12">
        <div class="col-12 text-center">
            <p class="h6"> GANANCIAS </p>
        </div>
        <div class="row justify-content-end">
            <div class="col-4">
                <div class="form-group mb-2">
                    <label for="fechaInicio" class="col-auto col-form-label">FECHA INICIAL</label>
                    <input type="date" class="form-control" id="fechaInicio" wire:model="fechaInicio" placeholder="Fecha inicio" >
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-2">
                    <label for="fechaFinal" class="col-auto col-form-label">FECHA FINAL</label>
                    <input type="date" class="form-control" id="fechaFinal" wire:model="fechaFinal" placeholder="Fecha final">
                </div>
            </div>
           
           {{--  <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Filtrar</button> &nbsp;
                <a href="/comision/lista/show" class="btn btn-primary ml-3">Limpiar Filtro</a>
            </div> --}}
            <div class="col-12 text-end">
                <a  wire:click="export" class="btn btn-primary ml-3">Exportar resultados</a>
            </div> 
        </div>
        <table class="table mt-5">
            <tr>
                <th>NOMBRE</th>
                <th>CANTIDAD</th>
                <th>COSTO</th>
                <th>TOTAL</th>
                @hasrole(['administrador'])
                    <th>GANANCIA</th>
                @endrole
            </tr>
            @foreach ($solicitudes as $solicitud)
            @php
                $porcentaje_ganancia = $solicitud->porcentaje_ganancia;
                $subtotal = $solicitud->precio_total / $solicitud->cantidad;
                $total = $solicitud->precio_total;
                $cantidad = $solicitud->cantidad; // Suponiendo que `$solicitud->cantidad` contiene el número de unidades

                // Calculamos el precio unitario
                $precio_unitario = $total / $cantidad;

                // Calculamos la ganancia en base al porcentaje aplicado al precio unitario
                $ganancia_unitaria = $precio_unitario * ($porcentaje_ganancia / 100);

                // Multiplicamos la ganancia unitaria por la cantidad para obtener la ganancia total
                $totalGanancia = format_price($ganancia_unitaria * $cantidad);

            @endphp
                <tr>
                    <td>
                        
                        {{ $solicitud->nombre }} {{ $solicitud->estatus ==3 ? 'Renovación' : '' }} 
                        @php
                            $pacientes = $MSolicitudPaciente::where('solicitud_id', $solicitud->id)
                                        ->with('paciente')->get();
                                        
                        @endphp
                        @foreach ($pacientes as $pacientesValue)
                        <span class="color-secondary">
                            <br> {{ $pacientesValue->paciente->name }}  {{ $pacientesValue->paciente->vapellido }}
                        </span>
                        @endforeach
                    </td>
                    <td>{{ $solicitud->cantidad }}</td>
                    <td> ${{ format_price($subtotal) }} </td>
                    
                       
                    <td> {{ format_price($solicitud->precio_total) }} </td>
                    
                    @hasrole(['administrador'])
                        @if ($solicitud->solicitud_origin_id == 4)
                            <td> ${{ $totalGanancia }} </td>
                            @else
                            <td>N/A</td>
                        @endif
                        
                    @endrole
                </tr>
            @endforeach
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $solicitudes->links() }}
    </div>
</div>
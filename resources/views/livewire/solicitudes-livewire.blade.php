<div class="row mt-3">
    <div class="col-12">
        <div class="col-12 text-center">
            <p class="h6"> SOlICITUDES <a href="/admin/solicitudes/create"  class="color-primary"><i class="fas fa-plus"></i></a> </p>
        </div>
        <div class="col-12 text-right">
            
        </div>
        <div class="col-12">
            
        </div>
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
               <tr>
                <td>{{ $solicitud->nombre }}</td>
                @hasrole(['administrador'])
                <td> {{ $solicitud->name }} {{ $solicitud->apellido }}  </td>
                @endrole
                <td>{{ date('d-m-Y', strtotime($solicitud->created_at)) }}</td>
                <td>{{ $solicitud->cantidad }}</td>
                <td>
                    @php
                        $fechaVencimiento = \Carbon\Carbon::parse($solicitud->updated_at)->addYear();
                    @endphp
                    {{ $fechaVencimiento->format('d-m-Y') }}
                </td>
                <td>
                    @switch($solicitud->estatus)
                        @case(1)
                            ACTIVO
                            @break
                        @case(2)
                            EN REVISIÓN
                            @break
                        @default
                            Pendiente
                    @endswitch
                </td>
                <td>
                    <a href="/admin/solicitudes/{{ $solicitud->id }}"  class="color-primary"><i class="fas fa-eye"></i></a>
                </td>
               </tr>
           @endforeach
        </table>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- usuaarios --}}
        @if ($solicitudes->catalog_prices_id == 2) 
            <form id="frm-renovar-solicitudes" action="#">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>EMAIL</th>
                                <th>APELLIDO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users && count($users) > 0)
                                @foreach($users->take($cantidad) as $index => $user)
                                    <tr>
                                        <td><input type="checkbox" name="users[]" value="{{ $user->id }}"> {{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->vapellido }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">No hay usuarios disponibles</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="solicitudId" value="{{ $solicitudId }}">
                <div class="col-12 text-end">
                    <button type="button" onclick="renovarSolicitudes()" class="btn btn-primary"> Renovar </button>
                </div>
            </form>
        @endif
        {{-- consultorios --}}
        @if ($solicitudes->catalog_prices_id == 3)
        <form id="frm-renovar-solicitudes" action="#">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>CONSULTORIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($cons && count($cons) > 0)
                            @foreach($cons->take($cantidad) as $index => $con)
                                <tr>
                                    <td><input type="checkbox" name="cons[]" value="{{ $con->idconsultorios }}"> {{ $con->vnumconsultorio }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No hay consultorios disponibles</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="solicitudId" value="{{ $solicitudId }}">
            <div class="col-12 text-end">
                <button type="button" onclick="renovarSolicitudes()" class="btn btn-primary"> Renovar </button>
            </div>
        </form>
        @endif
    </div>
</div>

<div class="col-12">
    <table class="table mt-3">
        <thead>
            <tr>
                <th>NOMBRE</th>
                <th>RFC</th>
                <th>DIRECCIÓN</th>
                <th>TELÉFONO</th>
                <th>FOLIO</th>
                <th>ACTIVO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clinicas as $clinica)
                <tr>
                    <td>{{ $clinica->tnombre }}</td>
                    <td> {{ $clinica->vrfc }} </td>
                    <td> {{ $clinica->tdireccion }} </td>
                    <td> {{ $clinica->ttelefono }} </td>
                    <td> {{ $clinica->vfolioclinica }} </td>
                    <td> {{ config('enums.status')[$clinica->istatus] }} </td>
                    <td class="col-2">
                        <a href="/admin/clinica/{{ $clinica->idclinica }}/edit" class="btn btn-primary"><i
                                class="fas fa-edit"></i></a>
                        <a href="#" onclick="deleteClinica({{ $clinica->idclinica }})" class="btn btn-danger"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

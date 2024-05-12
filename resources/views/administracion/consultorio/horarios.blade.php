@inject('consultaAsignado', 'App\Models\ConsultaAsignado')
<table class="table">
    <thead>
        <tr>
            <th>HORARIO</th>
            <th>L</th>
            <th>M</th>
            <th>M</th>
            <th>J</th>
            <th>V</th>
            <th>S</th>
            <th>D</th>
        </tr>
    </thead>
    <tbody>
        @foreach(['Mañana' => 1, 'Tarde' => 2, 'Noche' => 3] as $period => $iturno)
        @php
            $turno = array(1 => 'manana', 2 => 'tarde', 3=> 'noche')
        @endphp
            <tr>
                <td>{{ $period }}</td>
                @for($idia = 1; $idia <= 7; $idia++)
                    @php
                        $data_where = [
                            'idia' => $idia,
                            'iturno' => $iturno,
                            'idconsultorio' => $idconsultorio,
                            'iddoctor' => $userId,
                            'itipousr' => 1, //*revisar porque es el tipo de usuario
                        ];
                        //dd($data_where);
                        $queryConsultaAsignado    = $consultaAsignado::where($data_where);
                        $getQueryConsultaAsignado = $queryConsultaAsignado->first();
                        $numeror                  = $queryConsultaAsignado->count();
                        $disabled                 = $getQueryConsultaAsignado != null && $numeror <> 0 ? 'readOnly' : null;
                    @endphp
                    <td>
                        @php
                            $aliasId      = $idia."_". $iturno;
                            $aliasIdIni   = $turno[$iturno]."_ini[]";
                            $aliasIdFin   = $turno[$iturno]."_fin[]";
                        @endphp
                        <select name="{{ $aliasIdIni }}"  id="{{ $aliasId }}_ini" class="form-control select-horas" onchange="validateHours('{{ $aliasId }}')">
                            @for($hour = 0; $hour < 24; $hour++)
                                <option value="{{ $hour }}" {{ $getQueryConsultaAsignado!= null && $getQueryConsultaAsignado->ihorainicial == $hour ? 'selected' : null }}>{{ $hour }}</option>
                            @endfor
                        </select>
                        <br>
                        <select name="{{ $aliasIdFin }}"  id="{{ $aliasId }}_fin" class="form-control select-horas" onchange="validateHours('{{ $aliasId }}')">
                            @for($hour = 0; $hour < 24; $hour++)
                                <option value="{{ $hour }}" {{ $getQueryConsultaAsignado!= null && $getQueryConsultaAsignado->ihorafinal == $hour ? 'selected' : null }}>{{ $hour }}</option>
                            @endfor
                        </select>
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>



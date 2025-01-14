<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Ganancias</title>
    <style>
        body {
        font-family: 'Roboto', sans-serif;
        font-size: 10px;
    }

    small {
        font-size: 8px;
    }

    p {
        font-size: 12px;
    }

    .no-border {
        border: none !important;
    }

    body {
        margin-bottom: 40px;
    }

    .bg-blue {
        background-color: #4a86e8;
    }

    .bluebackground {
        background-color: #DFDFDF;
    }

    .no-spacing {
        border-spacing: 0px;
        border-collapse: collapse;

    }

    .color-white {
        color: white;
    }

    .color-gray {
        color: gray;
    }

    .thead>th {
        height: 30px;
        padding: 5px;
    }

    .border-table>td {
        border: 1px solid gray;
        padding-top: 0px;
        padding-left: 5px;
    }

    .text-right {
        text-align: right;
    }

    .small-legend {
        font-size: 8px;
    }

    .table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse !important;
        border-spacing:0 !important; /* Removes the cell spacing via CSS */

    }

    .table th,
    .table td {
        padding: 0.10rem;
        vertical-align: top;

    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 1px solid #eceeef;
    }

    .table tbody+tbody {
        border-top: 2px solid #eceeef;
    }

    .table .table {
        background-color: #fff;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.2rem;
    }

    

    .table-bordered {
        border: 1px solid #eceeef;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #eceeef;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-active,
    .table-active>th,
    .table-active>td {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover>td,
    .table-hover .table-active:hover>th {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-success,
    .table-success>th,
    .table-success>td {
        background-color: #dff0d8;
    }

    .table-hover .table-success:hover {
        background-color: #d0e9c6;
    }

    .table-hover .table-success:hover>td,
    .table-hover .table-success:hover>th {
        background-color: #d0e9c6;
    }

    .table-info,
    .table-info>th,
    .table-info>td {
        background-color: #d9edf7;
    }

    .table-hover .table-info:hover {
        background-color: #c4e3f3;
    }

    .table-hover .table-info:hover>td,
    .table-hover .table-info:hover>th {
        background-color: #c4e3f3;
    }

    .table-warning,
    .table-warning>th,
    .table-warning>td {
        background-color: #fcf8e3;
    }

    .table-hover .table-warning:hover {
        background-color: #faf2cc;
    }

    .table-hover .table-warning:hover>td,
    .table-hover .table-warning:hover>th {
        background-color: #faf2cc;
    }

    .table-danger,
    .table-danger>th,
    .table-danger>td {
        background-color: #f2dede;
    }

    .table-hover .table-danger:hover {
        background-color: #ebcccc;
    }

    .table-hover .table-danger:hover>td,
    .table-hover .table-danger:hover>th {
        background-color: #ebcccc;
    }

    .thead-inverse th {
        color: #fff;
        background-color: #292b2c;
    }

    .thead-default th {
        color: #464a4c;
        background-color: #eceeef;
    }

    .table-inverse {
        color: #fff;
        background-color: #292b2c;
    }

    .table-inverse th,
    .table-inverse td,
    .table-inverse thead th {
        border-color: #fff;
    }

    .table-inverse.table-bordered {
        border: 0;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .table-responsive.table-bordered {
        border: 0;
    }

    
    @page { margin: 65px 50px; }
    #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 150px; background-color: white; }
    #footer .page:after { content: counter(page, upper-roman); }

    .very-small small {
        font-size: 8px;
    }
    small {
        font-size: 8px;
    }

    strong {
        font-size: 8px;
    }

    .table td:nth-child(2) {
        text-align: right;
    }

    header {
        position: fixed;
        top: -60px;
        left: 0;
        right: 0;
        height: 50px;
    }

    .color-blue{
        color: #03045E ;
    }

    .bluebackground{
        background-color: #CAF0F8;
    }
    body {
    background-image: url('http://test.sidecc.xyz/image/logo-2_resized.png');
    background-repeat: repeat;
    background-size: auto;
    width: 100%;
    opacity: 0.1;
}
        
    </style>
</head>
<body>
    @inject('MSolicitudPaciente', 'App\Models\SolicitudPaciente') 
    <table class="table">
        <tr>
            <td class="text-right"> <b>Fecha del reporte {{ date('Y-m-d')}} </b> </td>
        </tr>
    </table>
    @php
         $sumTotal = 0;
    @endphp
    <table class="payments color-blue"  data-show-columns="true" style="width: 100%; margin-top : 50px">
       <thead>
        <tr>
            <th>USUARIO PROPIETARIO</th>
            <th>SOLICITUD</th>
            <th>PACIENTE</th>
            <th>CANTIDAD</th>
            {{-- <th>COSTO</th> --}}
             @hasrole(['administrador'])
                <th>GANANCIA</th>
            @endrole
            <th>TOTAL</th>
           
        </tr>
       </thead>
        @foreach ($ganancias as $ganancia)
            @php
                $pacientes = $MSolicitudPaciente::where('solicitud_id', $ganancia->id)
                  ->with('paciente')->get();
                $porcentaje_ganancia = $ganancia->porcentaje_ganancia;
                $subtotal = $ganancia->precio_total / $ganancia->cantidad;
                $total = $ganancia->precio_total;
                $cantidad = $ganancia->cantidad; // Suponiendo que `$solicitud->cantidad` contiene el número de unidades

                // Calculamos el precio unitario
                $precio_unitario = $total / $cantidad;

                // Calculamos la ganancia en base al porcentaje aplicado al precio unitario
                $ganancia_unitaria = $precio_unitario * ($porcentaje_ganancia / 100);

                // Multiplicamos la ganancia unitaria por la cantidad para obtener la ganancia total
                $totalGanancia = format_price($ganancia_unitaria * $cantidad);

                $valorTotal = $ganancia->catalog_prices_id != 4 ? $ganancia->precio_total  : $totalGanancia;
                $sumTotal += $valorTotal;
                
            @endphp
            <tbody>
                <tr class="{{ $loop->index % 2 == 0 ? 'bluebackground' : '' }}">
                    <td style="text-align: center"> {{ $ganancia->name }} {{ $ganancia->vapellido }}  </td>
                    <td style="text-align: center"> {{ $ganancia->nombre }} </td>
                    <td style="text-align: center"> 
                        @if ($ganancia->catalog_prices_id == 1 || $ganancia->catalog_prices_id == 2 || $ganancia->catalog_prices_id == 3)
                            N/A
                            @else
                                @foreach ($pacientes as $pacientesValue)
                                <span class="color-secondary">
                                     {{ $pacientesValue->paciente->name }}  {{ $pacientesValue->paciente->vapellido }} <br>
                                </span>
                                @endforeach
                        @endif
                    </td>
                 
                    <td style="text-align: center">{{ $ganancia->cantidad }}</td>
                   {{--  <td> ${{ format_price($subtotal) }} </td> --}}
                   @hasrole(['administrador'])
                   @if ($ganancia->catalog_prices_id == 4)
                       <td> ${{ $totalGanancia }} </td>
                       @else
                       <td>N/A</td>
                   @endif
                       
               @endrole
                    <td style="text-align: center">{{ format_price($ganancia->precio_total) }}</td>
                   
                </tr>
            </tbody>
            
        @endforeach
        <tr>
            <td colspan="5" style="text-align: right">TOTAL</td>
            <td> {{ format_price($sumTotal)}} </td>
        </tr>
    </table>
</body>
</html>
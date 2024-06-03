<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receta</title>
</head>
<body>
    <style>
        table{
            font-size: 14px;
        }
        .hr{
            border: 0.1px solid grey !important;
        }
    </style>
    <table>
        <tr>
            <td colspan="2">
                <p> <b>Dr. {{ $medico->name }} {{ $medico->vapellido }}</b></p>
            </td>
           
        </tr>
        <tr>
            <td>{{  $medico->especialidad }}</td>
            <td>CED. PROF. {{  $medico->vcedula }}</td>
            <td>
               <b> FECHA Y HORA DE ELABORACIÓN: {{ date('Y-m-d H:i')}}</b>
            </td>
        </tr>
    </table>
    <hr class="hr">
    <table style="width: 100%">
        <tr>
            <td > <b>NOMBRE:</b> </td>
            <td> {{ $paciente->name }} {{ $paciente->vapellido }} </td>
            <td><b>NÚMERO DE EXPEDIENTE:</b>  </td>
            <td>{{ $paciente->codigo_paciente}}</td>
        </tr>
    </table>
    <hr class="hr">
</body>
</html>
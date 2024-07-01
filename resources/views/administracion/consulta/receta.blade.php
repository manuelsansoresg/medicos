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
        table {
            font-size: 14px;
        }

        .hr {
            border: 0.1px solid grey !important;
        }

        @page {
            margin: 50px 25px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        .content {
            margin-bottom: 50px;
            /* Space for the footer */
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding-top: 5px;
            font-size: 14px;
        }

        .footer .line {
            width: 50%;
            margin: 0 auto;
            border-top: 1px solid black;
            padding-top: 5px;
        }
    </style>
    <div class="content">
        <div style="width: 100%; text-align:center">
            <h3>RECETA</h3>
        </div>

        <div style="width: 100%; text-align:right">
            <b> FECHA Y HORA DE ELABORACIÓN:</b> {{ date('Y-m-d H:i') }}
        </div>
        <hr class="hr">
        <div style="width: 100%; text-align:center; padding-bottom: 10px">
            <b>DATOS DEL PACIENTE </b>
        </div>
        <table style="width: 100%">
            <tr>
                <td> <b>NOMBRE:</b> </td>
                <td> {{ $paciente->name }} {{ $paciente->vapellido }} </td>
                <td></td>
                <td></td>
                <td><b>NÚMERO DE EXPEDIENTE:</b> </td>
                <td>{{ $paciente->codigo_paciente }}</td>
            </tr>
            <tr>
                <td><b>SEXO</b></td>
                <td>
                    @if ($paciente->sexo != 'SELECCIONE UNA OPCIÓN')
                        {{ $paciente->sexo }}
                    @endif
                </td>
                <td><b>EDAD</b></td>
                <td>
                    @php
                        $fechaNacimiento = $paciente->fecha_nacimiento;
                        // Crear un objeto Carbon a partir de la fecha de nacimiento
                        $fechaNacimientoCarbon = Carbon\Carbon::parse($fechaNacimiento);
                        // Obtener la edad en años
                        $edad = $fechaNacimientoCarbon->age;
                    @endphp
                    {{ $edad }}
                </td>
                <td><b>FECHA DE NACIMIENTO</b></td>
                <td>
                    {{ $fechaNacimiento }}
                </td>
                
            </tr>
            <tr>
                <td><b>PESO</b></td>
                <td>{{ $consulta->peso }}</td>
                <td><b>ESTATURA</b></td>
                <td>{{ $consulta->estatura }}</td>
                @if ($consulta->temperatura != '')
                <td><b>TEMPERATURA</b></td>
                <td>
                    {{ $consulta->temperatura }}
                </td>
                @endif
               
            </tr>
        </table>
        <hr class="hr">
        <div style="width: 100%; text-align:center; padding-bottom: 0px">
            <b>DATOS DEL MÉDICO </b>
        </div>

        <table style="width: 100%">
            <tr>
                <td> <b>NOMBRE DEL MÉDICO</b> </td>
                <td>
                    <p> <b>Dr. {{ $medico->name }} {{ $medico->vapellido }}</b></p>
                </td>

            </tr>
            <tr>
                <td><b>CEDULA PROFESIONAL</b></td>
                <td>  {{ $medico->vcedula }}</td>
            </tr>
        </table>
        <hr class="hr">
        
        <br>
        <table>
            <tr>
                <td><b>EXPLORACIÓN MEDICA</b></td>
            </tr>
            <tr>
                <td>{{ $consulta->exploracion }}</td>
            </tr>
            <tr>
                <td><b>TRATAMIENTO</b></td>
            </tr>
           
            <tr>
                <td>{{ $consulta->receta }}</td>
            </tr>
            <tr>
                <td><b>ALERGIAS</b></td>
            </tr>
            <tr>
                <td>{{ $paciente->alergias }}</td>
            </tr>

        </table>
        <br>
        <div style="border: 0.5px solid black; padding: 5px">
            <b>INDICACIONES GENERALES</b>
            <p>{{ $consulta->indicaciones_generales }}</p>
            <p><b>FAVOR DE TOMAR EL TRATAMIENTO COMPLETO </b></p>
        </div>
    </div>
    <div class="footer">
        
        <div class="line">
            Firma
        </div>
        
        
    </div>
</body>

</html>

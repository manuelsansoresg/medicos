<?php

namespace App\Exports;

use App\Models\Solicitud;
use App\Models\SolicitudPaciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GananciaSolicitudesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;
    protected $fechaInicio;
    protected $fechaFinal;

    public function __construct($search, $fechaInicio, $fechaFinal)
    {
        $this->search = $search;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
    }

    public function collection()
    {
        // Obtiene los datos utilizando la función getGanancias
        return Solicitud::getGanancias(null, $this->search, $this->fechaInicio, $this->fechaFinal);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Paciente',
            'Cantidad',
            'Costo',
            'Total',
            'Ganancia',
            'Fecha de Activación',
            // Agrega los demás encabezados necesarios
        ];
    }

    public function map($solicitud): array
    {
        $porcentaje_ganancia = $solicitud->porcentaje_ganancia;
        $cantidad = $solicitud->cantidad;
        $precio_unitario = $solicitud->precio_total / $cantidad;
        $ganancia_unitaria = $precio_unitario * ($porcentaje_ganancia / 100);
        $totalGanancia = format_price($ganancia_unitaria * $cantidad);

        // Verifica si el catalog_prices_id es 4 para mostrar pacientes
        $pacientes = 'N/A';
        if ($solicitud->catalog_prices_id == 4) {
            $getPacientes = SolicitudPaciente::where('solicitud_id', $solicitud->id)
                                            ->with('paciente')->get();
            if ($getPacientes->isNotEmpty()) {
                // Usa "\n" para los saltos de línea en Excel
                $pacientes = $getPacientes->map(function ($getPaciente) {
                    return $getPaciente->paciente->name . ' ' . $getPaciente->paciente->vapellido;
                })->implode("\n");
            }
        }

        return [
            $solicitud->id,
            $solicitud->nombre,
            $pacientes,
            $solicitud->cantidad,
            format_price($precio_unitario),
            format_price($solicitud->precio_total),
            $totalGanancia,
            $solicitud->fecha_activacion,
            // Mapea otros campos según sea necesario
        ];
    }
}

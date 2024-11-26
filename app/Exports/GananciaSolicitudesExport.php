<?php

namespace App\Exports;

use App\Models\Solicitud;
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

        return [
            $solicitud->id,
            $solicitud->nombre,
            $solicitud->cantidad,
            format_price($precio_unitario),
            format_price($solicitud->precio_total),
            $totalGanancia,
            $solicitud->fecha_activacion,
            // Mapea otros campos según sea necesario
        ];
    }
}

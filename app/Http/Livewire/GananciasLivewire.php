<?php

namespace App\Http\Livewire;

use App\Exports\GananciaSolicitudesExport;
use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GananciasLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $fechaInicio     = null;
    public    $fechaFinal      = null;
    public    $limit;

    public function mount($limit)
    {
        $this->limit  = $limit;
        $this->fechaInicio  = date('Y-m-d');
        $this->fechaFinal = Carbon::now()->addMonth()->format('Y-m-d');

    }

    public function export()
    {
        /*  // Obtener los datos filtrados
        $ganancias = Solicitud::getGanancias(null, null, $this->fechaInicio, $this->fechaFinal);

        // Generar el PDF
        $pdf = Pdf::loadView('pdf_ganancias', compact('ganancias'));
        $pdf->setPaper('A4');

        // Guardar el PDF directamente en la raíz de 'public'
        $fileName = 'ganancias_' . date('Y_m_d') . '.pdf';
        $filePath = public_path($fileName); // Ubicación en la raíz de 'public'
        $pdf->save($filePath);

        // Emitir un evento para que JavaScript gestione la descarga
        $this->dispatchBrowserEvent('file-download', ['url' => asset($fileName)]); */
        return Excel::download(new GananciaSolicitudesExport($this->search, $this->fechaInicio, $this->fechaFinal), 'solicitudes.xlsx');
    }

    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $solicitudes = Solicitud::getGanancias(50, $this->search, $this->fechaInicio, $this->fechaFinal);
        return view('livewire.ganancias-livewire', compact('solicitudes'));
    }
}

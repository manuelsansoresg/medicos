<?php

namespace App\Http\Livewire;

use App\Exports\GananciaSolicitudesExport;
use App\Models\Solicitud;
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

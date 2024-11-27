<?php

namespace App\Http\Livewire;

use App\Models\CatalogPrice;
use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudesLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search              = '';
    public $solicitud_origin_id = null;
    public $isList              = '';
    public $isDownload          = false;
    public $limit;
    
    public function mount($limit, $isList = false)
    {
        $this->limit  = $limit;
        $this->isList = $isList;
        
    }

    
    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $catalogoSolicitudes = CatalogPrice::all();
        $solicitudes = Solicitud::getAll(50, $this->search, $this->solicitud_origin_id);
        return view('livewire.solicitudes-livewire', compact('solicitudes', 'catalogoSolicitudes'));
    }
}

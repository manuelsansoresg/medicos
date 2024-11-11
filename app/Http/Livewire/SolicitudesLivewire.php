<?php

namespace App\Http\Livewire;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class SolicitudesLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $isList          = '';
    public    $isDownload      = false;
    public    $limit;
    
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
        
        $solicitudes = Solicitud::getAll(50, $this->search);
        return view('livewire.solicitudes-livewire', compact('solicitudes'));
    }
}

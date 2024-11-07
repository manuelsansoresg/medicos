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
        $solicitudes = Solicitud::getAll();
        return view('livewire.solicitudes-livewire', compact('solicitudes'));
    }
}

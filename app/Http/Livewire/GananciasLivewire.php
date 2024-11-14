<?php

namespace App\Http\Livewire;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;

class GananciasLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $limit;

    public function mount($limit)
    {
        $this->limit  = $limit;
    }

    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $solicitudes = Solicitud::getGanancias(50, $this->search);
        return view('livewire.ganancias-livewire', compact('solicitudes'));
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Estudio;
use Livewire\Component;
use Livewire\WithPagination;

class EstudioLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $limit;
    public    $pacienteId;

    public function mount($limit, $pacienteId)
    {
        $this->limit      = $limit;
        $this->pacienteId = $pacienteId;
    }

    
    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $consultas      = Estudio::getByPaciente($this->pacienteId, $this->search, $this->limit, true);

        return view('livewire.estudio-livewire', compact('consultas'));
    }
}

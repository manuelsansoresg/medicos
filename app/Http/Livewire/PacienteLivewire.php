<?php

namespace App\Http\Livewire;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithPagination;

class PacienteLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $limit;

    public function mount($limit)
    {
        $this->limit = $limit;
    }
    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $pacientes = Paciente::getAll($this->search, $this->limit);
        return view('livewire.paciente-livewire', compact('pacientes'));
    }
}

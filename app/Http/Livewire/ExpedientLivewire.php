<?php

namespace App\Http\Livewire;

use App\Models\Consulta;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ExpedientLivewire extends Component
{
    use WithPagination; 
    protected $paginationTheme = 'bootstrap';
    public $limit;
    public $expedients;
    public $search          = '';

    public function mount($limit)
    {
        $this->limit = $limit;
    }
    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $pacientes = User::searchPacient($this->search, $this->limit);
        return view('livewire.expedient-livewire', compact('pacientes'));
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Consulta;
use App\Models\User;
use App\Models\UserCita;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultaLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public    $search          = '';
    public    $limit;
    public    $pacienteId;
    public    $isExpedient;

    public function mount($limit, $pacienteId, $isExpedient)
    {
        $this->limit      = $limit;
        $this->pacienteId = $pacienteId;
        $this->isExpedient = $isExpedient;
    }

    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $consultas      = Consulta::getByPaciente($this->pacienteId, $this->search, $this->limit, true);

        return view('livewire.consulta-livewire', compact('consultas'));
    }
}

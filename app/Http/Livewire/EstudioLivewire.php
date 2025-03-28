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
    public    $userCitaId;
    public    $ConsultaAsignado;
    public    $isExpedient;
    public $selectedTab = 'consultas'; // Valor inicial

    protected $listeners = ['updateSelectedTab'];

    public function updateSelectedTab($tabName)
    {
        $this->selectedTab = $tabName;
    }

    public function mount($limit, $pacienteId, $userCitaId = null, $ConsultaAsignado = null, $isExpedient = null)
    {
        $this->limit              = $limit;
        $this->pacienteId         = $pacienteId;
        $this->isExpedient        = $isExpedient;
        $this->userCitaId         = $userCitaId;
        $this->ConsultaAsignado = $ConsultaAsignado;
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

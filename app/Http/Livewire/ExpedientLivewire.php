<?php

namespace App\Http\Livewire;

use App\Models\ClinicaUser;
use App\Models\Consulta;
use App\Models\ConsultorioUser;
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
    public $my_clinics;
    public $my_consultories;
    public $clinica;
    public $consultorio;
    public $fecha_inicio;
    public $fecha_final;

    protected $listeners = ['updatedClinica', 'updatedConsultorio'];

    public function updatedClinica($value)
    {
        session(['clinica' => $value]);
    }

    public function updatedConsultorio($value)
    {
        session(['consultorio' => $value]);
    }

    public function mount($limit)
    {
        $this->limit = $limit;
        $this->my_clinics = ClinicaUser::myClinics();
        $this->my_consultories = ConsultorioUser::myConsultories();
        $this->clinica = session('clinica', '');
        $this->consultorio = session('consultorio', '');
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

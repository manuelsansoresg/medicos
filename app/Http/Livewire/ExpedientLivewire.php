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
        $this->my_clinics = ClinicaUser::myClinics()->map(function($item) {
            // Si ya es objeto Clinica, regresa ese
            if (isset($item->clinica)) {
                return $item->clinica;
            }
            // Si es UserCita, busca la clinica
            if (isset($item->id_clinica)) {
                return \App\Models\Clinica::find($item->id_clinica);
            }
            return null;
        })->filter(); // Elimina nulos

        $this->my_consultories = ConsultorioUser::myConsultories()->map(function($item) {
            if (isset($item->consultorio)) {
                return $item->consultorio;
            }
            if (isset($item->id_consultorio)) {
                return \App\Models\Consultorio::find($item->id_consultorio);
            }
            return null;
        })->filter();

        $this->clinica = session('clinica', '');
        $this->consultorio = session('consultorio', '');
    }

    private function filtrarPorSesionPaciente($coleccion)
    {
        if (!auth()->user() || !auth()->user()->hasRole('paciente')) {
            return $coleccion;
        }
        $sessionClinica = session('clinica');
        $sessionConsultorio = session('consultorio');
        return $coleccion->filter(function($item) use ($sessionClinica, $sessionConsultorio) {
            if (!isset($item->user_cita_id) || !$item->user_cita_id) return false;
            $userCita = \App\Models\UserCita::find($item->user_cita_id);
            if (!$userCita) return false;
            if (!$sessionClinica && !$sessionConsultorio) return true;
            return ($sessionClinica && $userCita->id_clinica == $sessionClinica)
                || ($sessionConsultorio && $userCita->id_consultorio == $sessionConsultorio);
        });
    }

    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }
        $pacientes = User::searchPacient($this->search, $this->limit);
        // Filtrar consultas y estudios para cada paciente si es paciente
        $pacientes->transform(function($paciente) {
            $paciente->consultas = $this->filtrarPorSesionPaciente(\App\Models\Consulta::getByPaciente($paciente->id, null, null, false, $this->fecha_inicio, $this->fecha_final));
            $paciente->estudios = $this->filtrarPorSesionPaciente(\App\Models\Estudio::getByPaciente($paciente->id, null, null, false, $this->fecha_inicio, $this->fecha_final));
            return $paciente;
        });
        return view('livewire.expedient-livewire', compact('pacientes'));
    }
}

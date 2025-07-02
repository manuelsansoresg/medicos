<?php

namespace App\Http\Livewire;

use App\Models\ClinicaUser;
use App\Models\ConsultorioUser;
use App\Models\UserCita;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ExpedientLivewire extends Component
{
    use WithPagination; 
    protected $paginationTheme = 'bootstrap';
    public $limit;
    public $search = '';
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
        $this->resetPage();
    }

    public function updatedConsultorio($value)
    {
        session(['consultorio' => $value]);
        $this->resetPage();
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

    public function render()
    {
        if ($this->search !== '' && $this->page > 1) {
            $this->resetPage();
        }

        // Construir filtros
        $filters = [
            'clinica' => $this->clinica,
            'consultorio' => $this->consultorio,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_final' => $this->fecha_final,
            'search' => $this->search
        ];

        // Obtener pacientes con citas usando la nueva estructura
        $pacientes = UserCita::getPacientesWithCitas($filters);
        
        // Los filtros de permisos se manejan en el modelo UserCita
        
        // Debug: verificar si hay datos
        if ($pacientes->isEmpty()) {
            // Si no hay datos, crear una colección vacía pero con estructura correcta
            $pacientes = collect();
        }
        
        // Aplicar paginación manual
        $total = $pacientes->count();
        $perPage = $this->limit ?: 50;
        $currentPage = $this->page;
        $offset = ($currentPage - 1) * $perPage;
        
        $pacientes = $pacientes->slice($offset, $perPage);
        
        // Crear objeto de paginación manual
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pacientes,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('livewire.expedient-livewire', compact('paginator'));
    }
}

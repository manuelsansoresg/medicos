<?php

namespace App\Http\Livewire;

use App\Models\Clinica;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PanelConfigurationLiveWire extends Component
{

    public $typeConfiguration = null;
    public $clinicas = [];
    
    // Propiedades para manejo de horarios
    public $horario_manana_inicio = 6;  // 6:00 AM
    public $horario_manana_fin = 12;    // 12:00 PM
    public $horario_tarde_inicio = 12;  // 12:00 PM
    public $horario_tarde_fin = 18;     // 6:00 PM
    public $horario_noche_inicio = 18;  // 6:00 PM
    public $horario_noche_fin = 22;     // 10:00 PM

    public function mount()
    {
        $this->typeConfiguration = Auth::user()->type_configuration;
        $this->clinicas  = Clinica::where('idusrregistra', Auth::user()->id)->get();
    }

    // Métodos para validar horarios (sin ajuste automático)
    public function updatedHorarioMananaInicio($value)
    {
        // Solo validar que esté en el rango permitido
        if ($value < 6) $this->horario_manana_inicio = 6;
        if ($value > 12) $this->horario_manana_inicio = 12;
    }

    public function updatedHorarioTardeInicio($value)
    {
        // Solo validar que esté en el rango permitido
        if ($value < 12) $this->horario_tarde_inicio = 12;
        if ($value > 18) $this->horario_tarde_inicio = 18;
    }

    public function updatedHorarioNocheInicio($value)
    {
        // Solo validar que esté en el rango permitido
        if ($value < 18) $this->horario_noche_inicio = 18;
        if ($value > 22) $this->horario_noche_inicio = 22;
    }

    // Método para obtener los horarios configurados
    public function getHorariosConfiguracion()
    {
        return [
            'manana' => [
                'inicio' => $this->horario_manana_inicio,
                'fin' => $this->horario_manana_fin
            ],
            'tarde' => [
                'inicio' => $this->horario_tarde_inicio,
                'fin' => $this->horario_tarde_fin
            ],
            'noche' => [
                'inicio' => $this->horario_noche_inicio,
                'fin' => $this->horario_noche_fin
            ]
        ];
    }

    public function render()
    {
        return view('livewire.panel-configuration-live-wire');
    }
}

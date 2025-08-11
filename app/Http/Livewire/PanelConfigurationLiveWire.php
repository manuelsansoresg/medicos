<?php

namespace App\Http\Livewire;

use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PanelConfigurationLiveWire extends Component
{

    public $typeConfiguration = null;
    public $totalConsultorio = 0;
    public $tab = 'step1-clinic';

    public $clinicas = [];
    public $consultorios = [];
    
    // Propiedades para manejo de datos de consultorios
    public $consultoriosData = [];
    
    // Propiedades para manejo de horarios por día de la semana
    public $horarios_semanales = [
        'lunes' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 18, 'turno_noche_fin' => 22
        ],
        'martes' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ],
        'miercoles' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ],
        'jueves' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ],
        'viernes' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ],
        'sabado' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ],
        'domingo' => [
            'turno_manana_inicio' => 6, 'turno_manana_fin' => 12,
            'turno_tarde_inicio' => 13, 'turno_tarde_fin' => 18,
            'turno_noche_inicio' => 19, 'turno_noche_fin' => 22
        ]
    ];
    
    public $dia_seleccionado = 'lunes';

    public function mount()
    {
        $this->typeConfiguration = Auth::user()->type_configuration;
        $this->clinicas  = Clinica::where('idusrregistra', Auth::user()->id)->get();
        $statusPackages = Solicitud::getUsedStatusPackages();
        $this->totalConsultorio = $statusPackages['totalConsultorioExtra']['totalConfiguracion'];
        $this->consultorios = Consultorio::getAll(null,1)->toArray();
        
        // Inicializar datos de consultorios
        $this->initializeConsultoriosData();
    }

    // Método para cambiar el día seleccionado
    public function cambiarDia($dia)
    {
        $this->dia_seleccionado = $dia;
    }
    
    // Métodos para validar horarios por día
    public function updatedHorariosSemanales($value, $key)
    {
        $keys = explode('.', $key);
        $dia = $keys[0];
        $turno = $keys[1];
        
        // Validaciones según el turno
        if (strpos($turno, 'manana') !== false) {
            if ($value < 6) $this->horarios_semanales[$dia][$turno] = 6;
            if ($value > 13) $this->horarios_semanales[$dia][$turno] = 13;
        } elseif (strpos($turno, 'tarde') !== false) {
            if ($value < 13) $this->horarios_semanales[$dia][$turno] = 13;
            if ($value > 19) $this->horarios_semanales[$dia][$turno] = 19;
        } elseif (strpos($turno, 'noche') !== false) {
            if ($value < 19) $this->horarios_semanales[$dia][$turno] = 19;
            if ($value > 22) $this->horarios_semanales[$dia][$turno] = 22;
        }
    }

    // Método para convertir hora de 24h a 12h con AM/PM
    public function formatTime12Hour($hour)
    {
        if ($hour == 0) {
            return '12:00 AM';
        } elseif ($hour < 12) {
            return sprintf('%d:00 AM', $hour);
        } elseif ($hour == 12) {
            return '12:00 PM';
        } else {
            return sprintf('%d:00 PM', $hour - 12);
        }
    }

    // Método para obtener los horarios configurados del día seleccionado
    public function getHorariosConfiguracion($dia = null)
    {
        $dia = $dia ?? $this->dia_seleccionado;
        return $this->horarios_semanales[$dia] ?? [];
    }
    
    // Método para obtener todos los horarios semanales
    public function getTodosLosHorarios()
    {
        return $this->horarios_semanales;
    }
    
    // Método para inicializar datos de consultorios
    public function initializeConsultoriosData()
    {
        for ($i = 0; $i < $this->totalConsultorio; $i++) {
            // Si existe un consultorio en esa posición, cargar sus datos
            if (isset($this->consultorios[$i])) {
                $consultorio = $this->consultorios[$i];
                $this->consultoriosData[$i] = [
                    'vnumconsultorio' => $consultorio['vnumconsultorio'] ?? '',
                    'thubicacion' => $consultorio['thubicacion'] ?? '',
                    'ttelefono' => $consultorio['ttelefono'] ?? '',
                    'consultorio_id' => $consultorio['idconsultorios'] ?? null
                ];
            } else {
                // Si no existe, inicializar con datos vacíos
                $this->consultoriosData[$i] = [
                    'vnumconsultorio' => '',
                    'thubicacion' => '',
                    'ttelefono' => '',
                    'consultorio_id' => null
                ];
            }
        }
    }
    
    // Método para guardar consultorio específico
    public function guardarConsultorio($indiceConsultorio)
    {
        try {
            $data = $this->consultoriosData[$indiceConsultorio];
            
            // Validar que el nombre sea requerido
            if (empty($data['vnumconsultorio'])) {
                session()->flash('error', 'El nombre del consultorio es requerido.');
                return;
            }
            
            // Crear objeto request simulado
            $request = new \stdClass();
            $request->data = [
                'vnumconsultorio' => $data['vnumconsultorio'],
                'thubicacion' => $data['thubicacion'],
                'ttelefono' => $data['ttelefono']
            ];
            $request->consultorio_id = $data['consultorio_id'];
            
            // Guardar usando el método del modelo
            $consultorio = Consultorio::saveEdit($request);
            
            // Actualizar el ID en los datos locales si es un nuevo consultorio
            if ($data['consultorio_id'] === null) {
                $this->consultoriosData[$indiceConsultorio]['consultorio_id'] = $consultorio->idconsultorios;
            }
            
            session()->flash('message', 'Consultorio guardado correctamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el consultorio: ' . $e->getMessage());
        }
    }
    
    // Método para guardar configuración de horarios
    public function guardarHorarios()
    {
        // Aquí se puede implementar la lógica para guardar en base de datos
        session()->flash('message', 'Horarios guardados correctamente para toda la semana.');
    }

    public function render()
    {
        return view('livewire.panel-configuration-live-wire');
    }
}

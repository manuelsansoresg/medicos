<?php

namespace App\Http\Livewire;

use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\ConsultaAsignado;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PanelConfigurationLiveWire extends Component
{

    public $typeConfiguration = null;
    public $totalConsultorio = 0;
    public $tab = 1;
    public $idClinica = null;

    public $clinicas = [];
    public $consultorios = [];
    
    // Propiedades para manejo de datos de clínica
    public $clinicaData = [
        'tnombre' => '',
        'tdireccion' => '',
        'vrfc' => '',
        'ttelefono' => '',
        'vfolioclinica' => ''
    ];
    
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
        $this->clinicaData  = Clinica::where('idusrregistra', Auth::user()->id)->first()->toArray();
        $this->idClinica = $this->clinicaData ? $this->clinicaData['idclinica'] : null;


        $statusPackages = Solicitud::getUsedStatusPackages();
        $this->totalConsultorio = $statusPackages['totalConsultorioExtra']['totalConfiguracion'];
        $this->consultorios = Consultorio::getAll(null,1)->toArray();
        
        // Inicializar datos de consultorios
        $this->initializeConsultoriosData();
    }
    
    // Método para guardar clínica
    public function guardarClinica()
    {
        try {
            // Validar campos requeridos
            if (empty($this->clinicaData['tnombre'])) {
                session()->flash('error', 'El nombre de la clínica es requerido.');
                return;
            }
            
            if (empty($this->clinicaData['vfolioclinica'])) {
                session()->flash('error', 'El folio de la clínica es requerido.');
                return;
            }
            
            // Crear objeto request simulado
            $request = new \stdClass();
            $request->data = $this->clinicaData;
            $request->clinica_id = $this->idClinica; // Nueva clínica
            
            // Guardar usando el método del modelo
            $clinica = Clinica::saveEdit($request);
            
            // Actualizar el ID de la clínica
            $this->idClinica = $clinica->idclinica;
            
            // Cambiar al siguiente tab
            $this->tab = 2;
            
            session()->flash('message', 'Clínica guardada correctamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la clínica: ' . $e->getMessage());
        }
    }
    
    // Computed property para verificar si el botón debe estar habilitado
    public function getBotonHabilitadoProperty()
    {
        return !empty($this->clinicaData['tnombre']) && !empty($this->clinicaData['vfolioclinica']);
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
                    'consultorio_id' => $consultorio['idconsultorios'] ?? null,
                    'duracion_consulta' => 30 // valor por defecto
                ];
            } else {
                // Si no existe, inicializar con datos vacíos
                $this->consultoriosData[$i] = [
                    'vnumconsultorio' => '',
                    'thubicacion' => '',
                    'ttelefono' => '',
                    'consultorio_id' => null,
                    'duracion_consulta' => 30 // valor por defecto
                ];
            }
        }
    }
    
    // Método para guardar consultorio específico
    public function guardarConsultorio($indiceConsultorio, $numberTab)
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
            $this->tab = $numberTab + 1;

            session()->flash('message', 'Consultorio guardado correctamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el consultorio: ' . $e->getMessage());
        }
    }
    
    // Método para guardar consultorio y horarios
    public function guardarConsultorioYHorarios($indiceConsultorio, $contConsultory)
    {
        try {
            // Primero guardar el consultorio
            $this->guardarConsultorio($indiceConsultorio, $contConsultory);
            
            // Verificar que el consultorio se guardó correctamente
            if (!isset($this->consultoriosData[$indiceConsultorio]['consultorio_id'])) {
                session()->flash('error', 'Error: No se pudo obtener el ID del consultorio.');
                return;
            }
            
            $consultorioId = $this->consultoriosData[$indiceConsultorio]['consultorio_id'];
            $duracionConsulta = $this->consultoriosData[$indiceConsultorio]['duracion_consulta'];
            
            // Guardar horarios para cada día de la semana
            $this->guardarHorarios($consultorioId, $duracionConsulta);
            
            session()->flash('message', 'Consultorio y horarios guardados correctamente.');
            
            // Emitir evento para hacer scroll a la sección de configuración
            $this->emit('scrollToConsultorioConfig');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar consultorio y horarios: ' . $e->getMessage());
        }
    }
    
    // Método para guardar configuración de horarios
    public function guardarHorarios($consultorioId, $duracionConsulta)
    {
        try {
            $userId = User::getMyUserPrincipal();
            
            // Eliminar horarios existentes para este consultorio y doctor
            ConsultaAsignado::where('idconsultorio', $consultorioId)
                           ->where('iddoctor', $userId)
                           ->delete();
            
            // Guardar horarios para cada día de la semana
            foreach ($this->horarios_semanales as $dia => $horarios) {
                $numeroDia = $this->getDiaNumero($dia);
                
                // Turno mañana
                if ($horarios['turno_manana_inicio'] < $horarios['turno_manana_fin']) {
                    ConsultaAsignado::create([
                        'iddoctor' => $userId,
                        'idclinica' => $this->idClinica,
                        'ihorainicial' => $horarios['turno_manana_inicio'],
                        'ihorafinal' => $horarios['turno_manana_fin'],
                        'idia' => $numeroDia,
                        'iturno' => 1, // mañana
                        'itiempo' => $duracionConsulta,
                        'dfechaalta' => now(),
                        'idalta' => 1,
                        'idconsultorio' => $consultorioId,
                        'itipousr' => 1
                    ]);
                }
                
                // Turno tarde
                if ($horarios['turno_tarde_inicio'] < $horarios['turno_tarde_fin']) {
                    ConsultaAsignado::create([
                        'iddoctor' => $userId,
                        'idclinica' => $this->idClinica,
                        'ihorainicial' => $horarios['turno_tarde_inicio'],
                        'ihorafinal' => $horarios['turno_tarde_fin'],
                        'idia' => $numeroDia,
                        'iturno' => 2, // tarde
                        'itiempo' => $duracionConsulta,
                        'dfechaalta' => now(),
                        'idalta' => 1,
                        'idconsultorio' => $consultorioId,
                        'itipousr' => 1
                    ]);
                }
                
                // Turno noche
                if ($horarios['turno_noche_inicio'] < $horarios['turno_noche_fin']) {
                    ConsultaAsignado::create([
                        'iddoctor' => $userId,
                        'idclinica' => $this->idClinica,
                        'ihorainicial' => $horarios['turno_noche_inicio'],
                        'ihorafinal' => $horarios['turno_noche_fin'],
                        'idia' => $numeroDia,
                        'iturno' => 3, // noche
                        'itiempo' => $duracionConsulta,
                        'dfechaalta' => now(),
                        'idalta' => 1,
                        'idconsultorio' => $consultorioId,
                        'itipousr' => 1
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            throw new \Exception('Error al guardar horarios: ' . $e->getMessage());
        }
    }

    public function anteriorConsultorio($tab)
    {
        $this->tab = $tab - 1;
    }

    
    // Método auxiliar para convertir nombre de día a número
     private function getDiaNumero($nombreDia)
     {
         $dias = [
             'lunes' => 1,
             'martes' => 2,
             'miercoles' => 3,
             'jueves' => 4,
             'viernes' => 5,
             'sabado' => 6,
             'domingo' => 7
         ];
         
         return $dias[$nombreDia] ?? 1;
     }
     
     // Método para verificar si hay al menos un consultorio con datos válidos
     public function getHayConsultorioValidoProperty()
     {
         foreach ($this->consultoriosData as $consultorio) {
             if (!empty($consultorio['vnumconsultorio'])) {
                 return true;
             }
         }
         return false;
     }

     public function finalizarConfiguracion()
     {
        $usuarioPrincipal = User::getMyUserPrincipal();
        User::find($usuarioPrincipal)->update([
            'is_config' => true
        ]);
        return redirect('/');
     }

    public function render()
    {
        return view('livewire.panel-configuration-live-wire');
    }
}

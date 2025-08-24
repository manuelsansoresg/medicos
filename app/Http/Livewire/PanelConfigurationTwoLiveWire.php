<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clinica;
use App\Models\Consultorio;
use App\Models\ConsultaAsignado;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PanelConfigurationTwoLiveWire extends Component
{
    protected $listeners = ['guardarDias' => 'guardarDiasLaborales'];
    
    public $typeConfiguration = null;
    public $totalConsultorio = 0;
    public $tab = 2;
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
    
    // Propiedades públicas para cada día
    public $lunes = false;
    public $martes = false;
    public $miercoles = false;
    public $jueves = false;
    public $viernes = false;
    public $sabado = false;
    public $domingo = false;
    public $dias_configurados = false;
    public $dias_laborales = [];

    
    // Opciones de horarios para los selects
    public $horarios_manana = [
        6 => '6:00 AM', 7 => '7:00 AM', 8 => '8:00 AM', 9 => '9:00 AM', 
        10 => '10:00 AM', 11 => '11:00 AM', 12 => '12:00 PM', 13 => '1:00 PM', 14 => '2:00 PM'
    ];
    
    public $horarios_tarde = [
        12 => '12:00 PM', 13 => '1:00 PM', 14 => '2:00 PM', 15 => '3:00 PM', 
        16 => '4:00 PM', 17 => '5:00 PM', 18 => '6:00 PM', 19 => '7:00 PM', 
        20 => '8:00 PM', 21 => '9:00 PM', 22 => '10:00 PM'
    ];
    
    public $horarios_noche = [
        18 => '6:00 PM', 19 => '7:00 PM', 20 => '8:00 PM', 21 => '9:00 PM', 
        22 => '10:00 PM', 23 => '11:00 PM', 0 => '12:00 AM', 1 => '1:00 AM', 2 => '2:00 AM'
    ];

    public function mount()
    {
        $this->typeConfiguration = Auth::user()->type_configuration;
        $this->cargarDatosClinica();
        
        $statusPackages = Solicitud::getUsedStatusPackages();
        $this->totalConsultorio = $statusPackages['totalConsultorioExtra']['totalConfiguracion'];
        $this->consultorios = Consultorio::getAll(null,1)->toArray();
        
        // Inicializar datos de consultorios
        $this->initializeConsultoriosData();
        
        // Inicializar propiedades de días explícitamente
        $this->lunes = false;
        $this->martes = false;
        $this->miercoles = false;
        $this->jueves = false;
        $this->viernes = false;
        $this->sabado = false;
        $this->domingo = false;
        
        \Log::info('Componente PanelConfigurationTwoLiveWire inicializado');
    }
    
    public function cargarDatosClinica()
    {
        $clinica = Clinica::where('idusrregistra', Auth::user()->id)->first();
        
        if ($clinica != null) {
            $clinicaArray = $clinica->toArray();
            $this->clinicaData = [
                'tnombre' => $clinicaArray['tnombre'] ?? '',
                'tdireccion' => $clinicaArray['tdireccion'] ?? '',
                'vrfc' => $clinicaArray['vrfc'] ?? '',
                'ttelefono' => $clinicaArray['ttelefono'] ?? '',
                'vfolioclinica' => $clinicaArray['vfolioclinica'] ?? ''
            ];
            $this->idClinica = $clinicaArray['idclinica'] ?? null;
        } else {
            $this->clinicaData = [
                'tnombre' => '',
                'tdireccion' => '',
                'vrfc' => '',
                'ttelefono' => '',
                'vfolioclinica' => ''
            ];
            $this->idClinica = null;
        }
        
        // Forzar actualización de Livewire
        $this->emit('refresh');
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
    
    // Computed property para verificar si el botón debe estar habilitado (mantenida para compatibilidad)

    
    // Método para actualizar el estado del botón

      
      
    

    
    // Método para cambiar el día seleccionado
    public function cambiarDia($dia)
    {
        $this->dia_seleccionado = $dia;
    }
    
    // Método para manejar la selección de días laborales
    public function toggleDiaLaboral($dia)
    {
        if (in_array($dia, $this->dias_laborales)) {
            $this->dias_laborales = array_diff($this->dias_laborales, [$dia]);
        } else {
            $this->dias_laborales[] = $dia;
        }
    }
    
    // Método para guardar la configuración de días laborales
    public function guardarDiasLaborales()
    {
        \Log::info('=== INICIO guardarDiasLaborales ===');
        \Log::info('Valores de días:', [
            'lunes' => $this->lunes,
            'martes' => $this->martes,
            'miercoles' => $this->miercoles,
            'jueves' => $this->jueves,
            'viernes' => $this->viernes,
            'sabado' => $this->sabado,
            'domingo' => $this->domingo
        ]);
        
        $diasSeleccionados = [];
        
        if ($this->lunes) $diasSeleccionados[] = 'lunes';
        if ($this->martes) $diasSeleccionados[] = 'martes';
        if ($this->miercoles) $diasSeleccionados[] = 'miercoles';
        if ($this->jueves) $diasSeleccionados[] = 'jueves';
        if ($this->viernes) $diasSeleccionados[] = 'viernes';
        if ($this->sabado) $diasSeleccionados[] = 'sabado';
        if ($this->domingo) $diasSeleccionados[] = 'domingo';
        
        \Log::info('Días seleccionados:', $diasSeleccionados);
        
        if (empty($diasSeleccionados)) {
            \Log::info('No hay días seleccionados');
            session()->flash('error', 'Debe seleccionar al menos un día laboral.');
            return;
        }
        
        \Log::info('Configurando días laborales...');
        $this->dias_configurados = true;
        $this->dias_laborales = $diasSeleccionados;
        
        // Reinicializar horarios solo para los días seleccionados
        $nuevos_horarios = [];
        foreach ($diasSeleccionados as $dia) {
            $nuevos_horarios[$dia] = [
                'turno_manana_inicio' => 6, 'turno_manana_fin' => 14,
                'turno_tarde_inicio' => 12, 'turno_tarde_fin' => 22,
                'turno_noche_inicio' => 18, 'turno_noche_fin' => 2
            ];
        }
        $this->horarios_semanales = $nuevos_horarios;
        
        // Establecer el primer día laboral como seleccionado
        $this->dia_seleccionado = $diasSeleccionados[0];
        
        \Log::info('Configuración completada', [
            'dias_configurados' => $this->dias_configurados,
            'dias_laborales' => $this->dias_laborales,
            'dia_seleccionado' => $this->dia_seleccionado
        ]);
        
        session()->flash('message', 'Días laborales configurados correctamente.');
        \Log::info('=== FIN guardarDiasLaborales ===');
    }
    
    public function testLivewire()
    {
        \Log::info('Método testLivewire ejecutado');
        session()->flash('message', 'Livewire funciona correctamente!');
    }
    
    public function procesarDias()
    {
        \Log::info('=== MÉTODO ALTERNATIVO procesarDias ===');
        $this->guardarDiasLaborales();
    }
    
    public function updatedLunes($value)
    {
        \Log::info('Lunes actualizado a: ' . ($value ? 'true' : 'false'));
    }
    
    public function updatedMartes($value)
    {
        \Log::info('Martes actualizado a: ' . ($value ? 'true' : 'false'));
    }
    
    public function updatedMiercoles($value)
    {
        \Log::info('Miércoles actualizado a: ' . ($value ? 'true' : 'false'));
    }
    
    // Método para volver a la selección de días
    public function volverSeleccionDias()
    {
        $this->dias_configurados = false;
        $this->dias_laborales = [];
    }
    
    // Métodos para validar horarios por día
    public function updatedHorariosSemanales($value, $key)
    {
        $keys = explode('.', $key);
        $dia = $keys[0];
        $turno = $keys[1];
        
        // Validaciones según el turno con los nuevos rangos
        if (strpos($turno, 'manana') !== false) {
            // Mañana: 6am a 2pm
            if (strpos($turno, 'inicio') !== false && $value < 6) {
                $this->horarios_semanales[$dia][$turno] = 6;
            }
            if (strpos($turno, 'fin') !== false && $value > 14) {
                $this->horarios_semanales[$dia][$turno] = 14;
            }
            // Validar que inicio sea menor que fin
            if (strpos($turno, 'inicio') !== false && $value >= $this->horarios_semanales[$dia]['turno_manana_fin']) {
                $this->horarios_semanales[$dia]['turno_manana_fin'] = $value + 1;
            }
            if (strpos($turno, 'fin') !== false && $value <= $this->horarios_semanales[$dia]['turno_manana_inicio']) {
                $this->horarios_semanales[$dia]['turno_manana_inicio'] = $value - 1;
            }
        } elseif (strpos($turno, 'tarde') !== false) {
            // Tarde: 12pm a 10pm
            if (strpos($turno, 'inicio') !== false && $value < 12) {
                $this->horarios_semanales[$dia][$turno] = 12;
            }
            if (strpos($turno, 'fin') !== false && $value > 22) {
                $this->horarios_semanales[$dia][$turno] = 22;
            }
            // Validar que inicio sea menor que fin
            if (strpos($turno, 'inicio') !== false && $value >= $this->horarios_semanales[$dia]['turno_tarde_fin']) {
                $this->horarios_semanales[$dia]['turno_tarde_fin'] = $value + 1;
            }
            if (strpos($turno, 'fin') !== false && $value <= $this->horarios_semanales[$dia]['turno_tarde_inicio']) {
                $this->horarios_semanales[$dia]['turno_tarde_inicio'] = $value - 1;
            }
        } elseif (strpos($turno, 'noche') !== false) {
            // Noche: 6pm a 2am (considerando el cambio de día)
            if (strpos($turno, 'inicio') !== false && $value < 18) {
                $this->horarios_semanales[$dia][$turno] = 18;
            }
            if (strpos($turno, 'fin') !== false && $value > 2 && $value < 18) {
                $this->horarios_semanales[$dia][$turno] = 2;
            }
            // Validación especial para horario nocturno que cruza medianoche
            if (strpos($turno, 'inicio') !== false && $value >= 18) {
                // Inicio válido
            } elseif (strpos($turno, 'fin') !== false && ($value <= 2 || $value >= 18)) {
                // Fin válido (puede ser hasta las 2am del día siguiente)
            }
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
            $numberConsultoru = Auth::user()->type_configuration == 1 ? $contConsultory -1 : $contConsultory;

            if ($this->totalConsultorio == $numberConsultoru) {
                $this->finalizarConfiguracion();
            }
            
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
        $this->dispatchBrowserEvent('configuracion-finalizada');
     }

    public function render()
    {
        \Log::info('Método render ejecutado', [
            'dias_configurados' => $this->dias_configurados,
            'lunes' => $this->lunes,
            'martes' => $this->martes
        ]);
        return view('livewire.panel-configuration-two-live-wire');
    }
}

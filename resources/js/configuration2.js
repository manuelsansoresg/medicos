// Funciones de validación para configuración de horarios

// Validar que los horarios no se solapen entre turnos
function validarSolapamientoHorarios(dia, turno, tipo, valor) {
    const horarios = window.livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).horarios_semanales[dia];
    
    if (!horarios) return true;
    
    // Convertir horarios a minutos para facilitar comparación
    const convertirAMinutos = (hora) => {
        if (hora <= 2) return (hora + 24) * 60; // Horario nocturno que cruza medianoche
        return hora * 60;
    };
    
    const valorMinutos = convertirAMinutos(valor);
    
    // Validaciones específicas por turno
    switch (turno) {
        case 'manana':
            if (tipo === 'inicio') {
                // No debe solaparse con turno de tarde
                const tardeInicio = convertirAMinutos(horarios.turno_tarde_inicio);
                if (valorMinutos >= tardeInicio) {
                    mostrarError('El horario de mañana no puede solaparse con el turno de tarde');
                    return false;
                }
            }
            break;
            
        case 'tarde':
            if (tipo === 'inicio') {
                // No debe empezar antes que termine la mañana
                const mananaFin = convertirAMinutos(horarios.turno_manana_fin);
                if (valorMinutos < mananaFin) {
                    mostrarError('El turno de tarde debe empezar después del turno de mañana');
                    return false;
                }
            }
            if (tipo === 'fin') {
                // No debe solaparse con turno de noche
                const nocheInicio = convertirAMinutos(horarios.turno_noche_inicio);
                if (valorMinutos > nocheInicio) {
                    mostrarError('El turno de tarde no puede solaparse con el turno de noche');
                    return false;
                }
            }
            break;
            
        case 'noche':
            if (tipo === 'inicio') {
                // No debe empezar antes que termine la tarde
                const tardeFin = convertirAMinutos(horarios.turno_tarde_fin);
                if (valorMinutos < tardeFin) {
                    mostrarError('El turno de noche debe empezar después del turno de tarde');
                    return false;
                }
            }
            break;
    }
    
    return true;
}

// Mostrar mensaje de error
function mostrarError(mensaje) {
    // Crear o actualizar elemento de error
    let errorElement = document.getElementById('horario-error');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'horario-error';
        errorElement.className = 'alert alert-danger mt-2';
        errorElement.style.display = 'none';
        document.querySelector('.horario-section').appendChild(errorElement);
    }
    
    errorElement.textContent = mensaje;
    errorElement.style.display = 'block';
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        errorElement.style.display = 'none';
    }, 3000);
}

// Validar horario específico según el turno
function validarHorarioTurno(turno, tipo, valor) {
    switch (turno) {
        case 'manana':
            if (valor < 6 || valor > 14) {
                mostrarError('El turno de mañana debe estar entre 6:00 AM y 2:00 PM');
                return false;
            }
            break;
            
        case 'tarde':
            if (valor < 12 || valor > 22) {
                mostrarError('El turno de tarde debe estar entre 12:00 PM y 10:00 PM');
                return false;
            }
            break;
            
        case 'noche':
            if (valor < 18 && valor > 2) {
                mostrarError('El turno de noche debe estar entre 6:00 PM y 2:00 AM');
                return false;
            }
            break;
    }
    
    return true;
}

// Inicializar validaciones cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listeners a los selects de horarios
    document.addEventListener('change', function(e) {
        if (e.target.matches('select[wire\\:model*="horarios_semanales"]')) {
            const wireModel = e.target.getAttribute('wire:model');
            const parts = wireModel.split('.');
            
            if (parts.length >= 3) {
                const dia = parts[1];
                const turnoTipo = parts[2].split('_');
                const turno = turnoTipo[1]; // manana, tarde, noche
                const tipo = turnoTipo[2]; // inicio, fin
                const valor = parseInt(e.target.value);
                
                // Validar horario
                if (!validarHorarioTurno(turno, tipo, valor)) {
                    e.preventDefault();
                    return false;
                }
                
                // Validar solapamiento
                if (!validarSolapamientoHorarios(dia, turno, tipo, valor)) {
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
});

// Función para limpiar errores
function limpiarErrores() {
    const errorElement = document.getElementById('horario-error');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}
/**
 * Script para manejo de sliders de horarios
 * Mejora la experiencia de usuario con los controles de rango de tiempo
 */

document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar la barra visual del rango
    function updateRangeTrack(container, minValue, maxValue, minRange, maxRange) {
        const track = container.querySelector('.range-track');
        if (track) {
            const range = maxRange - minRange;
            const left = ((minValue - minRange) / range) * 100;
            const width = ((maxValue - minValue) / range) * 100;
            
            track.style.left = left + '%';
            track.style.width = width + '%';
        }
    }

    // Función para validar que el valor mínimo no sea mayor al máximo
    function validateRange(minInput, maxInput) {
        // Validación removida para permitir movimiento independiente de sliders
        // Los sliders ahora pueden moverse libremente sin restricciones dinámicas
    }

    // Inicializar sliders para cada sección de horario
    const horarioSections = document.querySelectorAll('.horario-section');
    
    horarioSections.forEach(section => {
        const minInput = section.querySelector('.range-input-min');
        const maxInput = section.querySelector('.range-input-max');
        
        if (minInput && maxInput) {
            const minRange = parseInt(minInput.min);
            const maxRange = parseInt(maxInput.max);
            
            // Eventos para el slider mínimo
            minInput.addEventListener('input', function() {
                updateRangeTrack(section, parseInt(minInput.value), parseInt(maxInput.value), minRange, maxRange);
            });
            
            // Eventos para el slider máximo
            maxInput.addEventListener('input', function() {
                updateRangeTrack(section, parseInt(minInput.value), parseInt(maxInput.value), minRange, maxRange);
            });
            
            // Inicializar la barra visual
            updateRangeTrack(section, parseInt(minInput.value), parseInt(maxInput.value), minRange, maxRange);
        }
    });

    // Función para formatear la hora en formato 12 horas
    function formatTime12Hour(hour) {
        if (hour === 0) return '12:00 AM';
        if (hour < 12) return hour + ':00 AM';
        if (hour === 12) return '12:00 PM';
        return (hour - 12) + ':00 PM';
    }

    // Función para formatear la hora en formato 24 horas
    function formatTime24Hour(hour) {
        return hour.toString().padStart(2, '0') + ':00';
    }

    // Mejorar la interacción con los sliders
    const rangeInputs = document.querySelectorAll('input[type="range"]');
    rangeInputs.forEach(input => {
        input.setAttribute('title', 'Arrastra para ajustar la hora');
        
        // Mejorar la respuesta al mouse
        input.addEventListener('mousedown', function(e) {
            this.style.cursor = 'grabbing';
            document.body.style.userSelect = 'none';
        });
        
        input.addEventListener('mouseup', function(e) {
            this.style.cursor = 'grab';
            document.body.style.userSelect = '';
        });
        
        // Mejorar la interacción táctil
        input.addEventListener('touchstart', function(e) {
            this.style.cursor = 'grabbing';
        });
        
        input.addEventListener('touchend', function(e) {
            this.style.cursor = 'grab';
        });
        
        // Event listener de click removido para evitar interferencia entre sliders
    });

    // Función para mostrar feedback visual cuando se cambian los valores
    function showFeedback(section, message) {
        const existingFeedback = section.querySelector('.horario-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        const feedback = document.createElement('div');
        feedback.className = 'horario-feedback alert alert-success mt-2';
        feedback.style.cssText = 'font-size: 0.9rem; padding: 8px 12px; opacity: 0; transition: opacity 0.3s ease;';
        feedback.textContent = message;
        
        section.appendChild(feedback);
        
        // Mostrar con animación
        setTimeout(() => {
            feedback.style.opacity = '1';
        }, 100);
        
        // Ocultar después de 2 segundos
        setTimeout(() => {
            feedback.style.opacity = '0';
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.parentNode.removeChild(feedback);
                }
            }, 300);
        }, 2000);
    }

    // Agregar feedback cuando se cambian los horarios
    document.addEventListener('livewire:updated', function() {
        const sections = document.querySelectorAll('.horario-section');
        sections.forEach(section => {
            const title = section.querySelector('.horario-title').textContent;
            showFeedback(section, `${title} actualizado correctamente`);
        });
    });
});
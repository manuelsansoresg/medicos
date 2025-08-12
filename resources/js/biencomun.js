$(document).ready(function() {
    // Establecer fecha y hora actual por defecto
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    const currentTime = now.toTimeString().slice(0, 5);
    
    $('#date').val(today);
    $('#hour').val(currentTime);
    
    // Variable para controlar si estamos editando
    let isEditing = false;

    // Configurar token CSRF para todas las peticiones AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Envío del formulario
    $('#formBienComun').on('submit', function(e) {
        e.preventDefault();
        
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const bienComunId = $('#bien_comun_id').val();
        const isUpdate = bienComunId && bienComunId !== '';
        
        const formData = {
            name: $('#name').val(),
            user_id: $('#user_id').val(),
            date: $('#date').val(),
            hour: $('#hour').val(),
            description: $('#description').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        if (isUpdate) {
            formData._method = 'PUT';
        }
        
        const url = isUpdate ? `/admin/bien-comun/${bienComunId}` : '/admin/bien-comun';
        const method = 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#modalBienComun').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', response.message || 'Error al guardar el bien común');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const input = $('#' + key);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(errors[key][0]);
                    });
                } else {
                    showAlert('error', 'Error al procesar la solicitud');
                }
            }
        });
    });
    
    // Filtros
    $('#filtroMes, #filtroUsuario').on('change', function() {
        aplicarFiltros();
    });
    

});

// Función para aplicar filtros
function aplicarFiltros() {
    const mesSeleccionado = $('#filtroMes').val();
    const usuarioSeleccionado = $('#filtroUsuario').val();
    
    $('.calendar-day-card').each(function() {
        let mostrarDia = false;
        
        $(this).find('.bien-comun-item').each(function() {
            const mesItem = $(this).data('month');
            const usuarioItem = $(this).data('user-id');
            
            let mostrarItem = true;
            
            // Filtro por mes
            if (mesSeleccionado && mesItem != mesSeleccionado) {
                mostrarItem = false;
            }
            
            // Filtro por usuario
            if (usuarioSeleccionado && usuarioItem != usuarioSeleccionado) {
                mostrarItem = false;
            }
            
            if (mostrarItem) {
                $(this).show();
                mostrarDia = true;
            } else {
                $(this).hide();
            }
        });
        
        // Mostrar/ocultar el día completo
        if (mostrarDia) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Función para eliminar bien común
window.deleteBienComun = function(id) {

    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este bien común?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
      }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: '/admin/bien-comun/' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        
                        // Recargar página
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    let message = 'Error al eliminar el bien común';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('error', message);
                }
            });
        } 
      })


    
}

// Función para mostrar alertas
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remover alertas existentes
    $('.alert').remove();
    
    // Agregar nueva alerta
    $('.card-body').prepend(alertHtml);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Función para limpiar formulario cuando se cierra el modal
$('#modalBienComun').on('hidden.bs.modal', function() {
    $('#formBienComun')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    
    // Limpiar ID oculto y resetear modo
    $('#bien_comun_id').val('');
    isEditing = false;
    
    // Cambiar título del modal
    $('#modalBienComunLabel').html('<i class="fas fa-plus-circle me-2"></i>Agregar Bien Común');
    
    // Restablecer fecha y hora actual
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    const currentTime = now.toTimeString().slice(0, 5);
    
    $('#date').val(today);
    $('#hour').val(currentTime);
});

// Función para editar bien común (fuera del scope de jQuery para acceso global)
window.editBienComun = function(id, name, userId, date, hour, description) {
    // Cambiar título del modal
    $('#modalBienComunLabel').html('<i class="fas fa-edit me-2"></i>Editar Bien Común');
    
    // Llenar el formulario con los datos
    $('#bien_comun_id').val(id);
    $('#name').val(name);
    $('#user_id').val(userId);
    $('#date').val(date);
    $('#hour').val(hour);
    $('#description').val(description);
    
    // Abrir el modal
    $('#modalBienComun').modal('show');
}
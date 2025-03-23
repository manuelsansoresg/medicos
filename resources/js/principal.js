window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
$(document).ready(function() {
    // Variables
    let hasCedula = null;
    let selectedPackage = null;
    let packageName = "";
    let packagePrice = 0;
    
    // Paso 1: Selección de tipo de registro
    $('.custom-radio').click(function() {
        $('.custom-radio').removeClass('selected');
        $(this).addClass('selected');
        
        if ($(this).attr('id') === 'with-cedula') {
            hasCedula = true;
        } else {
            hasCedula = false;
        }
        
        $('#next-to-step2').prop('disabled', false);
    });
    
    // Modificado: Ahora solo adjuntamos el evento después de cargar los paquetes
    function attachPackageEvents() {
        // Paso 2: Selección de paquete
        $('.package-card').click(function() {
            $('.package-card').removeClass('selected');
            $(this).addClass('selected');
            selectedPackage = $(this).attr('id');
            packageName = $(this).data('package-name');
            packagePrice = $(this).data('package-price');
            $('#next-to-step3').prop('disabled', false);
        });
    }
    
    // Navegación hacia adelante
    $('#next-to-step2').click(function() {
        $('#step1').removeClass('active');
        $('#step2').addClass('active');
        $('#step1-indicator').removeClass('active').addClass('completed');
        $('#step2-indicator').addClass('active');
        
        // Cargar paquetes según la selección de cédula
        loadPackages(hasCedula ? 1 : 0);
    });
    
    // Función para cargar paquetes
    function loadPackages(isValidateCedula) {
        // Mostrar indicador de carga
        $('#packages-container').html('<div class="col-12 text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando paquetes...</p></div>');
        
        // Hacer petición a la ruta que devolverá el HTML de los paquetes
        axios.get('/obtener-paquetes', {
            params: {
                isValidateCedula: isValidateCedula
            }
        })
        .then(function(response) {
            // Insertar HTML en el contenedor
            $('#packages-container').html(response.data);
            
            // Adjuntar eventos a los nuevos elementos
            attachPackageEvents();
        })
        .catch(function(error) {
            console.error('Error al cargar paquetes:', error);
            $('#packages-container').html('<div class="col-12 text-center"><p>Error al cargar paquetes. Por favor, intente nuevamente.</p></div>');
        });
    }
    
    $('#next-to-step3').click(function() {
        $('#step2').removeClass('active');
        $('#step3').addClass('active');
        $('#step2-indicator').removeClass('active').addClass('completed');
        $('#step3-indicator').addClass('active');
        
        // Actualizar el resumen y los campos ocultos con los valores seleccionados
        const tipoRegistroTexto = hasCedula ? 'Con Cédula Profesional' : 'Sin Cédula Profesional';
        
        // Actualizar el resumen visual
        $('#summary-registro-tipo').text(tipoRegistroTexto);
        $('#summary-paquete-nombre').text(packageName);
        $('#summary-paquete-precio').text('$' + packagePrice + '/mes');
        
        // Actualizar los campos ocultos del formulario
        $('#tipo-registro').val(hasCedula ? 'con_cedula' : 'sin_cedula');
        $('#paquete-id').val(selectedPackage);
        $('#paquete-nombre').val(packageName);
        $('#paquete-precio').val(packagePrice);
        
        // Mostrar u ocultar campos de cédula según la selección
        if (hasCedula) {
            // Si eligió "Con Cédula", mostrar el campo y hacerlo requerido
            $('#cedula-field').show();
            $('#cedula').prop('required', true);
            $('#especialidad').prop('required', true);
        } else {
            // Si eligió "Sin Cédula", ocultar el campo y quitar el required
            $('#cedula-field').hide();
            $('#cedula').prop('required', false);
            $('#especialidad').prop('required', false);
            
            // Asignar valores vacíos a los campos ocultos
            $('#cedula').val('');
            $('#especialidad').val('');
        }
    });
    
    // Navegación hacia atrás
    $('#back-to-step1').click(function() {
        $('#step2').removeClass('active');
        $('#step1').addClass('active');
        $('#step2-indicator').removeClass('active');
        $('#step1-indicator').removeClass('completed').addClass('active');
    });
    
    $('#back-to-step2').click(function() {
        $('#step3').removeClass('active');
        $('#step2').addClass('active');
        $('#step3-indicator').removeClass('active');
        $('#step2-indicator').removeClass('completed').addClass('active');
    });
    
    // Limpiar errores previos
    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }
    
    // Mostrar errores en los campos correspondientes
    function displayErrors(errors) {
        $.each(errors, function(field, messages) {
            const input = $('[name="' + field + '"]');
            input.addClass('is-invalid');
            
            // Eliminar mensajes de error anteriores
            input.siblings('.invalid-feedback').remove();
            
            // Agregar nuevo mensaje de error
            const errorMessage = '<span class="invalid-feedback" role="alert"><strong>' + messages[0] + '</strong></span>';
            input.after(errorMessage);
        });
        
        // Desplazarse al primer error
        if ($('.is-invalid').length > 0) {
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    }
    
    // Envío del formulario
    $('#registration-form').submit(function(e) {
        e.preventDefault();
        
        // Limpiar errores previos
        clearErrors();
        
        // Validar que las contraseñas coincidan
        if ($('#password').val() !== $('#confirm-password').val()) {
            $('#password, #confirm-password').addClass('is-invalid');
            $('#confirm-password').after('<span class="invalid-feedback" role="alert"><strong>Las contraseñas no coinciden</strong></span>');
            return;
        }
        
        // Mostrar indicador de carga
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
        submitBtn.prop('disabled', true);
        
        // Obtener el token CSRF del formulario para incluirlo en la solicitud
        const token = $('input[name="_token"]').val();
        
        // Obtener todos los datos del formulario
        const formData = new FormData(this);
        
        // Enviar los datos al servidor usando axios
        axios.post('/register', formData, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function(response) {
            console.log(response.data);
            let user = response.data.user;
            // Registro exitoso
            $('#step3').removeClass('active');
            $('#step4').addClass('active');
            $('#step3-indicator').removeClass('active').addClass('completed');
            $('#step4-indicator').addClass('active');
            
            // Update payment summary
            $('#payment-package-name').text($('#summary-paquete-nombre').text());
            $('#payment-registration-type').text($('#summary-registro-tipo').text());
            $('#payment-total').text($('#summary-paquete-precio').text());
            
            //window.location.href = response.data.redirect || '/home';
        })
        .catch(function(error) {
            // Restaurar el botón de envío
            submitBtn.html(originalBtnText);
            submitBtn.prop('disabled', false);
            
            if (error.response) {
                if (error.response.status === 422) {
                    // Errores de validación
                    displayErrors(error.response.data.errors);
                } else {
                    // Otro tipo de error
                    alert('Ha ocurrido un error. Por favor, intente nuevamente.');
                }
            } else {
                alert('Ha ocurrido un error de conexión. Por favor, intente nuevamente.');
            }
        });
    });

    $('#next-to-step4').click(function() {
        // Validate step 3 fields first
        if (validateStep3()) {
            $('#step3').removeClass('active');
            $('#step4').addClass('active');
            $('#step3-indicator').removeClass('active');
            $('#step4-indicator').addClass('active');
            
            // Update payment summary
            $('#payment-package-name').text($('#summary-paquete-nombre').text());
            $('#payment-registration-type').text($('#summary-registro-tipo').text());
            $('#payment-total').text($('#summary-paquete-precio').text());
        }
    });
    
    $('#back-to-step3').click(function() {
        $('#step4').removeClass('active');
        $('#step3').addClass('active');
        $('#step4-indicator').removeClass('active');
        $('#step3-indicator').addClass('active');
    });
    
    function registerPayment(CardTokenID){
        let paqueteId = $('#paquete-id').val();
        let user_id = $('#user_id').val();
        return axios.post('/payment', {
            paquete_id: paqueteId,
            user_id: user_id,
            card_token_id: CardTokenID
        })
        .then(function(response) {
            console.log('Payment registered successfully:', response.data);
            return response.data;
        })
        .catch(function(error) {
            console.error('Error registering payment:', error);
            throw error;
        });
    }

    $('#complete-payment').click(async function(e) {
        e.preventDefault();
        
        try {
            // Obtén el token de la tarjeta
            const cardToken = await card.cardToken();
            
            // Guarda el Card Token ID de la tarjeta en una constante
            const cardTokenID = cardToken.id;
            registerPayment(cardTokenID);
            
            // Aquí puedes agregar el código para enviar el cardTokenID a tu servidor
            
        } catch (error) {
            // Maneja errores durante la tokenización de la tarjeta
            switch (error.code) {
                case "CL2200":
                case "CL2290":
                    alert("Error: " + error.message);
                    throw error;
                case "AI1300":
                    console.log("Error: ", error.message);
                    break;
                default:
                    break;
            }
        }
    });
    
    // Basic card validation
    $('#card-number').on('input', function() {
        $(this).val($(this).val().replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim());
    });
    
    $('#card-expiry').on('input', function() {
        $(this).val($(this).val().replace(/[^\d]/g, '').replace(/^(\d{2})/, '$1/').trim());
    });
    
    $('#card-cvv').on('input', function() {
        $(this).val($(this).val().replace(/[^\d]/g, '').trim());
    });
});


/* pago por clip */
const API_KEY = "test_0ec19121-9fdc-4c07-907b-a1b23707e747"; //Aquí va tu API Key, no es necesario agregar nada más

// Inicializa el SDK de Clip con la API Key proporcionada
const clip = new ClipSDK(API_KEY);

// Verifica si la API Key ha sido ingresada correctamente


// Crea un elemento tarjeta con el SDK de Clip
const card = clip.element.create("Card", {
theme: "light",
locale: "es",
});
card.mount("checkout");
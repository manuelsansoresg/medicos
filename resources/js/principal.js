window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
$(document).ready(function () {
    console.log('Document ready iniciado');
    // Variables
    let hasCedula = null;
    let selectedEstablishment = null;
    let selectedPackage = null;
    let packageName = "";
    let packagePrice = 0;
    let termsAccepted = false;

    // Paso 1: Selección de tipo de registro
    $('#step1 .custom-radio').click(function () {
        $('#step1 .custom-radio').removeClass('selected');
        $(this).addClass('selected');

        if ($(this).attr('id') === 'with-cedula') {
            hasCedula = true;
        } else {
            hasCedula = false;
        }

        $('#next-to-step2').prop('disabled', false);
    });

    // Paso 2: Selección de tipo de establecimiento
    $('#step2 .custom-radio').click(function () {
        console.log('Establecimiento seleccionado:', $(this).attr('id'));
        $('#step2 .custom-radio').removeClass('selected');
        $(this).addClass('selected');

        if ($(this).attr('id') === 'clinica') {
            selectedEstablishment = 'clinica';
        } else {
            selectedEstablishment = 'consultorio';
        }

        console.log('selectedEstablishment:', selectedEstablishment);
        $('#next-to-step3').prop('disabled', false);
    });

    // Modificado: Ahora solo adjuntamos el evento después de cargar los paquetes
    function attachPackageEvents() {
        console.log('Adjuntando eventos a paquetes');
        // Paso 3: Selección de paquete
        $('.package-card').click(function () {
            console.log('Paquete seleccionado:', $(this).attr('id'));
            $('.package-card').removeClass('selected');
            $(this).addClass('selected');
            selectedPackage = $(this).attr('id');
            packageName = $(this).data('package-name');
            packagePrice = $(this).data('package-price');
            $('#next-to-step4').prop('disabled', false);
        });
    }

    // Navegación hacia adelante
    $('#next-to-step2').click(function () {
        $('#step1').removeClass('active');
        $('#step2').addClass('active');
        $('#step1-indicator').removeClass('active').addClass('completed');
        $('#step2-indicator').addClass('active');
    });

    $('#next-to-step3').click(function () {
        console.log('Navegando al paso 3');
        $('#step2').removeClass('active');
        $('#step3').addClass('active');
        $('#step2-indicator').removeClass('active').addClass('completed');
        $('#step3-indicator').addClass('active');

        // Cargar paquetes según la selección de cédula
        loadPackages(hasCedula ? 1 : 0);
    });

    // Función para cargar paquetes
    function loadPackages(isValidateCedula) {
        console.log('Cargando paquetes con:', { isValidateCedula, selectedEstablishment });
        
        // Mostrar indicador de carga
        $('#packages-container').html('<div class="col-12 text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando paquetes...</p></div>');

        // Hacer petición a la ruta que devolverá el HTML de los paquetes
        axios.get('/obtener-paquetes', {
            params: {
                isValidateCedula: isValidateCedula,
                owner_type: selectedEstablishment
            }
        })
            .then(function (response) {
                console.log('Paquetes cargados:', response.data);
                // Insertar HTML en el contenedor
                $('#packages-container').html(response.data);

                // Adjuntar eventos a los nuevos elementos
                attachPackageEvents();
            })
            .catch(function (error) {
                console.error('Error al cargar paquetes:', error);
                $('#packages-container').html('<div class="col-12 text-center"><p>Error al cargar paquetes. Por favor, intente nuevamente.</p></div>');
            });
    }

    $('#next-to-step4').click(function () {
        console.log('Navegando al paso 4');
        $('#step3').removeClass('active');
        $('#step4').addClass('active');
        $('#step3-indicator').removeClass('active').addClass('completed');
        $('#step4-indicator').addClass('active');

        // Actualizar el resumen y los campos ocultos con los valores seleccionados
        const tipoRegistroTexto = hasCedula ? 'Con Cédula Profesional' : 'Sin Cédula Profesional';
        const tipoEstablecimientoTexto = selectedEstablishment === 'clinica' ? 'Clínica' : 'Consultorio';

        console.log('Resumen:', { tipoRegistroTexto, tipoEstablecimientoTexto, packageName, packagePrice });

        // Actualizar el resumen visual
        $('#summary-registro-tipo').text(tipoRegistroTexto);
        $('#summary-establecimiento-tipo').text(tipoEstablecimientoTexto);
        $('#summary-paquete-nombre').text(packageName);
        $('#summary-paquete-precio').text('$' + packagePrice + '/mes');

        // Actualizar los campos ocultos del formulario
        $('#tipo-registro').val(hasCedula ? 'con_cedula' : 'sin_cedula');
        $('#tipo-establecimiento').val(selectedEstablishment);
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
    $('#back-to-step1').click(function () {
        $('#step2').removeClass('active');
        $('#step1').addClass('active');
        $('#step2-indicator').removeClass('active');
        $('#step1-indicator').removeClass('completed').addClass('active');
    });

    $('#back-to-step2').click(function () {
        $('#step3').removeClass('active');
        $('#step2').addClass('active');
        $('#step3-indicator').removeClass('active');
        $('#step2-indicator').removeClass('completed').addClass('active');
    });

    $('#back-to-step3').click(function () {
        $('#step4').removeClass('active');
        $('#step3').addClass('active');
        $('#step4-indicator').removeClass('active');
        $('#step3-indicator').removeClass('completed').addClass('active');
    });

    $('#back-to-step4').click(function () {
        $('#step5').removeClass('active');
        $('#step4').addClass('active');
        $('#step5-indicator').removeClass('active');
        $('#step4-indicator').removeClass('completed').addClass('active');
    });

    $('#back-to-step5').click(function () {
        $('#step6').removeClass('active');
        $('#step5').addClass('active');
        $('#step6-indicator').removeClass('active');
        $('#step5-indicator').removeClass('completed').addClass('active');
    });

    // Limpiar errores previos
    function clearErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }

    // Mostrar errores en los campos correspondientes
    function displayErrors(errors) {
        $.each(errors, function (field, messages) {
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
    $('#registration-form').submit(function (e) {
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
            .then(function (response) {
                // Restaurar el botón de envío
                submitBtn.html(originalBtnText);
                submitBtn.prop('disabled', false);

                console.log(response.data);
                let user = response.data.user;
                $('#user_id').val(user.id);
                // Registro exitoso
                $('#step4').removeClass('active');
                $('#step5').addClass('active');
                $('#step4-indicator').removeClass('active').addClass('completed');
                $('#step5-indicator').addClass('active');

                // Inicializar términos y condiciones
                initializeTermsAndConditions();

                //window.location.href = response.data.redirect || '/home';
            })
            .catch(function (error) {
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

    // Función para inicializar términos y condiciones
    function initializeTermsAndConditions() {
        console.log('Inicializando términos y condiciones...');
        
        const checkbox = document.getElementById('accept-terms');
        const nextButton = document.getElementById('next-to-step6');
        
        // Verificar que los elementos existan
        if (!checkbox) {
            console.error('No se encontró el checkbox de términos y condiciones');
            return;
        }
        
        if (!nextButton) {
            console.error('No se encontró el botón de continuar');
            return;
        }
        
        console.log('Elementos encontrados correctamente');

        // Manejar cambio del checkbox
        checkbox.addEventListener('change', function() {
            termsAccepted = this.checked;
            $('#accepted-terms').val(termsAccepted);
            nextButton.disabled = !termsAccepted;
            
            console.log('Checkbox cambiado:', termsAccepted);
            
            // Mostrar mensaje de confirmación
            if (this.checked) {
                const notification = document.createElement('div');
                notification.className = 'alert alert-success mt-3';
                notification.innerHTML = '<i class="fas fa-check-circle"></i> Términos y condiciones aceptados. Puede continuar con el proceso de pago.';
                checkbox.parentElement.appendChild(notification);
                
                // Remover notificación después de 3 segundos
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 3000);
            }
        });

        // Manejar navegación al paso de pago
        $('#next-to-step6').click(function() {
            if (termsAccepted) {
                $('#step5').removeClass('active');
                $('#step6').addClass('active');
                $('#step5-indicator').removeClass('active').addClass('completed');
                $('#step6-indicator').addClass('active');

                // Update payment summary
                $('#payment-package-name').text($('#summary-paquete-nombre').text());
                $('#payment-registration-type').text($('#summary-registro-tipo').text());
                $('#payment-total').text($('#summary-paquete-precio').text());
                
                console.log('Navegando al paso de pago');
            } else {
                console.log('No se puede continuar - términos no aceptados');
                alert('Debe aceptar los términos y condiciones para continuar con el proceso de pago.');
            }
        });
    }

    $('#back-to-step3').click(function () {
        $('#step4').removeClass('active');
        $('#step3').addClass('active');
        $('#step4-indicator').removeClass('active');
        $('#step3-indicator').addClass('active');
    });

    // Función para registrar el pago
    function registerPayment(CardTokenID) {
        let paqueteId = $('#paquete-id').val();
        let user_id = $('#user_id').val();
        return axios.post('/payment', {
            paquete_id: paqueteId,
            user_id: user_id,
            card_token_id: CardTokenID
        })
            .then(function (response) {
                console.log('Payment registered successfully:', response.data);
                window.location.href = '/registro-exitoso';
            })
            .catch(function (error) {
                console.error('Error registering payment:', error);
                $('#errorPaymentModal').modal('show');
                throw error;
            });
    }

    $('#complete-payment').click(async function (e) {
        e.preventDefault();
        let paqueteId = $('#paquete-id').val();
        let user_id = $('#user_id').val();

        const form = document.getElementById("payment-form");
        const data = new FormData(form);
        data.append('paquete_id', paqueteId);
        data.append('user_id', user_id);
        axios
            .post("/payment/transfer/save", data)
            .then(function (response) {
                window.location.href = '/registro-exitoso-transfer';
            })
            .catch(e => { });
    });

    // Basic card validation
    $('#card-number').on('input', function () {
        $(this).val($(this).val().replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim());
    });

    $('#card-expiry').on('input', function () {
        $(this).val($(this).val().replace(/[^\d]/g, '').replace(/^(\d{2})/, '$1/').trim());
    });

    $('#card-cvv').on('input', function () {
        $(this).val($(this).val().replace(/[^\d]/g, '').trim());
    });
});

// Función global para registrar el pago (accesible desde fuera del document.ready)
window.registerPayment = function(CardTokenID) {
    let paqueteId = $('#paquete-id').val();
    let user_id = $('#user_id').val();
    return axios.post('/payment', {
        paquete_id: paqueteId,
        user_id: user_id,
        card_token_id: CardTokenID
    })
        .then(function (response) {
            console.log('Payment registered successfully:', response.data);
            window.location.href = '/registro-exitoso';
        })
        .catch(function (error) {
            console.error('Error registering payment:', error);
            $('#errorPaymentModal').modal('show');
            throw error;
        });
};



if (document.getElementById('cardPayment')) {

    document.addEventListener('DOMContentLoaded', function () {
        const cardPayment = document.getElementById('cardPayment');
        const transferPayment = document.getElementById('transferPayment');
        const cardContent = document.getElementById('cardPaymentContent');
        const transferContent = document.getElementById('transferPaymentContent');
        const checkoutDiv = document.getElementById('checkout');

        function togglePaymentContent() {
            if (cardPayment.checked) {
                cardContent.style.display = 'block';
                transferContent.style.display = 'none';
                checkoutDiv.style.display = 'block';
            } else {
                cardContent.style.display = 'none';
                transferContent.style.display = 'block';
                checkoutDiv.style.display = 'none';
            }
        }

        cardPayment.addEventListener('change', togglePaymentContent);
        transferPayment.addEventListener('change', togglePaymentContent);
    });
}




/* pago por clip */
document.addEventListener('DOMContentLoaded', function () {
    try {
        const API_KEY = "test_0ec19121-9fdc-4c07-907b-a1b23707e747";

        // Verificar que ClipSDK está disponible
        if (typeof ClipSDK === 'undefined') {
            console.warn("ClipSDK no disponible");
            return;
        }

        // Inicializar SDK y crear elemento tarjeta
        const clip = new ClipSDK(API_KEY);
        console.log('clip');
        console.log(clip);
        // Continuar solo si clip se inicializó correctamente
        if (clip && clip.element) {
            const card = clip.element.create("Card", {
                theme: "light",
                locale: "es",
            });

            // Montar tarjeta si el elemento existe
            const checkoutElement = document.getElementById("checkout");
            if (checkoutElement) {
                card.mount("checkout");
                // Maneja el evento de envío del formulario
                // Maneja el evento de envío del formulario
                document.querySelector("#payment-form").addEventListener("submit", async (event) => {
                    event.preventDefault();
                    try {

                        // Obtén el token de la tarjeta
                        const cardToken = await card.cardToken();

                        // Guarda el Card Token ID de la tarjeta en una constante
                        const cardTokenID = cardToken.id;
                        console.log("Card Token ID:", cardTokenID);
                        
                        // Esperar a que se complete el registro del pago
                        await registerPayment(cardTokenID);

                    } catch (error) {

                        // Maneja errores durante la tokenización de la tarjeta
                        switch (error.code) {
                            case "CL2200":
                            case "CL2290":
                                alert("Error: " + error.message);
                                break;
                            case "AI1300":
                                console.log("Error: ", error.message);
                                break;
                            default:
                                console.error("Error en el proceso de pago:", error);
                                alert("Error en el proceso de pago. Por favor, intente nuevamente.");
                                break;
                        }
                    }
                });
            }
        }
    } catch (error) {
        // Capturar cualquier error pero permitir que el resto del código siga funcionando
        console.warn("Error con ClipSDK:", error.message || "Error desconocido");
    }

    // El resto de tu código seguirá ejecutándose normalmente
});


$("#comprobanteForm").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("comprobanteForm");
    const data = new FormData(form);
    let solicitudId = $('#solicitudId').val();
    axios
        .post("/solicitud/" + solicitudId + "/comprobante/adjuntar/store", data)
        .then(function (response) {
            window.location = '/solicitud/' + solicitudId + '/comprobante/exitoso';
        })
        .catch(e => { });
});

window.setPaymentMethod  = function(method) {
    if (method == 1) {
        $('#complete-payment').hide();
        $('#submit').show();
    } else {
        $('#complete-payment').show();
        $('#submit').hide();
        
    }
}
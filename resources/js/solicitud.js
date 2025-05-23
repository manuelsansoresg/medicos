$("#frm-solicitud").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-solicitud");
    const data = new FormData(form);

    axios
        .post("/admin/solicitudes", data)
        .then(function (response) {
            let result = response.data;
            let solicitud = result.solicitud;
            let isReturnError = result.isReturnError;
            let errorMessage = result.errorMessage;

            if (isReturnError === true) {
                Swal.fire({
                    text: errorMessage,
                    icon: "warning"
                }).then((result) => {
                    // Acción después de cerrar el alerta
                });
            } else {
                window.location = '/admin/solicitudes/' + solicitud.id;
            }
        })
        .catch(e => {
            console.error(e);
        });
});



window.saveAndNotifySolicitud = function(type)
{
    $('#isNotify').val(1);
    console.log('submitting form...');
    $('#spinner').show();
    const form = document.getElementById('frm-solicitud-validacion');
    const formData = new FormData(form);
    
    axios.post(form.action, formData)
        .then(function(response) {
            let title = type == 0 ? '¡Comprobante adjuntado correctamente!' : '¡Datos guardados correctamente!';
            let description = type == 0 ? 'Activaremos su cuenta en 24 horas tras verificar la información. <br>Le avisaremos por correo. <br>Puede ver el estatus en la página principal de su cuenta.' : '';
            Swal.fire({
                title: title,
                html: description,
                icon: "success"
            }).then((result) => {
                // Acción después de cerrar el alerta
                window.location = '/home';
            });
        })
        .catch(function(error) {
            console.error('Error submitting form:', error);
            if (error.response) {
                // Handle validation errors or other server responses
                console.error('Server response:', error.response.data);
            }
        });
}

window.setSolicitud = function(getSolicitud)
{
    let solicitud = getSolicitud.value;
    let cantidadInput = document.getElementById("cantidad");
    //TODO: borrar $('#content-solicitud-pacientes').hide();
    // Establece el valor máximo del input en función de la solicitud
    if (solicitud == 0) {
        cantidadInput.setAttribute("max", "1");
    } 
    //TODO: borrar
   /*  if(solicitud == 4) {
        $('#content-solicitud-pacientes').show();
    } */
    if(solicitud != 1) {
        cantidadInput.removeAttribute("max"); // Quita el atributo max si no es 1
    }
}
let selectedButtons = []; // Mantendrá un seguimiento de los botones seleccionados
let selectedIds = []; // Mantiene el ID de los pacientes seleccionados

window.toggleSelectionWithLimit = function(pacienteId) {
    const cantidadInput = document.getElementById("cantidad");
    const maxCantidad = parseInt(cantidadInput.value);
    
    // Verifica que el input "cantidad" tenga un valor válido
    if (!maxCantidad) {
        alert("Debes establecer una cantidad de pacientes.");
        cantidadInput.focus();
        return;
    }

    const button = document.getElementById(`btn-${pacienteId}`);
    const pacienteInput = document.getElementById("pacienteId");

    // Verifica que el botón y el input existan
    if (button && pacienteInput) {
        // Si el botón está en "btn-primary" y aún no se ha alcanzado el límite
        if (button.classList.contains("btn-primary") && selectedButtons.length < maxCantidad) {
            button.classList.remove("btn-primary");
            button.classList.add("btn-success");
            selectedButtons.push(button); // Agrega el botón a la lista de seleccionados
            selectedIds.push(pacienteId); // Agrega el ID al array de IDs seleccionados
            permisosPaciente(pacienteId);
            

        // Si el botón ya está seleccionado y el usuario hace clic para deseleccionarlo
        } else if (button.classList.contains("btn-success")) {
            button.classList.remove("btn-success");
            button.classList.add("btn-primary");
            selectedButtons = selectedButtons.filter(btn => btn !== button); // Quita el botón de la lista de seleccionados
            selectedIds = selectedIds.filter(id => id !== pacienteId); // Quita el ID del array de seleccionados
        } else if (selectedButtons.length >= maxCantidad) {
            alert(`Solo puedes seleccionar hasta ${maxCantidad} paciente(s)., dar clic al botón verde para deseleccionar `);
        }

        // Actualiza el valor del input oculto con los IDs seleccionados
        pacienteInput.value = selectedIds.join(',');
    }
}

window.permisosPaciente = function(pacientId)
{
    axios
        .get('/admin/usuarios/'+pacientId+'/permisos/get')
        .then(function (response) {
            let data = response.data;
            $('#content-pacient-permissions').html(data);
            $('#modalPermisosPaciente').modal('show');
            
        })
        .catch(e => { });
}

$("#frm-config-download-pacient-expedient").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-config-download-pacient-expedient");
    const data = new FormData(form);
    
    axios
        .post("/admin/configuracion-descargas", data)
        .then(function (response) {
            $('#modalPermisosPaciente').modal('hide');
        })
        .catch(e => { });
});

window.renew = function(SolicitudId)
{
    axios
        .get('/admin/solicitudes/'+SolicitudId+'/reset')
        .then(function (response) {
            let data = response.data;
            Swal.fire({
                text: 'Su solicitud ha sido enviada, favor de terminar el proceso de pago para su activación',
                icon: "warning"
            }).then((result) => {
                // Acción después de cerrar el alerta
                window.location = '/admin/solicitudes/'+data.id;
            });
            
        })
        .catch(e => { });
}

window.modalRenewSolicitud = function(solicitudId)
{
    axios
    .get('/admin/solicitudes/'+solicitudId+'/renew/showData')
    .then(function (response) {
        let data = response.data;
        $('#modalReactivacionLabel').html(data.title);
        $('#modalReactivacionContent').html(data.content);
        $('#modalReactivacion').modal('show');
        
    })
    .catch(e => { });
}

window.renovarSolicitudes = function()
{
    const form = document.getElementById("frm-renovar-solicitudes");
    const data = new FormData(form);

    axios
        .post('/admin/solicitudes/action/renew/store', data)
        .then(function (response) {
            Swal.fire({
                text: 'Su solicitud ha sido enviada, favor de adjuntar el comprobante de pago en la siguiente ventana para su activación',
                icon: "warning"
            }).then((result) => {
                // Acción después de cerrar el alerta
                window.location = '/';
            });
        })
        .catch(function (error) {
            console.error(error.response.data);
            alert("Ocurrió un error al renovar la solicitud");
        });
}


window.validarCedula = function(userId, solicitudId)
{
    const isCedulaValid = document.querySelector('input[name="is_cedula_valid"]:checked').value;
    
    axios
        .post('/admin/solicitudes/'+userId+'/'+solicitudId+'/cedula/validate', {
            is_cedula_valid: isCedulaValid // Incluye el valor en el cuerpo del POST
        })
        .then(function (response) {
            
            window.location = '/admin/solicitudes/'+solicitudId;
           /*  Swal.fire({
                text: 'Su solicitud ha sido enviada, favor de adjuntar el comprobante de pago en la siguiente ventana para su activación',
                icon: "warning"
            }).then((result) => {
                // Acción después de cerrar el alerta
                window.location = '/';
            }); */
        })
        .catch(function (error) {
            console.error(error.response.data);
            
        });
}




$("#frm-solicitud-comentario").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-solicitud-comentario");
    const data = new FormData(form);
    let solicitudId = $('#solicitudId').val();
    
    axios
        .post('/admin/solicitudes/'+solicitudId+'/comment/store', data)
        .then(function (response) {
            let result = response.data;
            Swal.fire({
                text: errorMessage,
                icon: "warning"
            }).then((result) => {
                // Acción después de cerrar el alerta
            });
            window.location = '/admin/solicitudes/'+solicitudId;
        })
        .catch(e => { });
});

$("#frm-solicitud-validacion").submit(function (e) {
    e.preventDefault();
    $('#spinner').show();
    
    // Aquí puedes agregar un pequeño retraso si quieres que el spinner sea visible
    setTimeout(function() {
        // Esto envía el formulario de forma programática
        e.target.submit();
        // Alternativamente: $(e.target)[0].submit();
    }, 200); // 500ms de retraso para que se vea el spinner
});

window.comentar = function(commentId)

{
    if (commentId == null) {
        $('#commentSolicitudModalTitle').html('Comentario');
        $('#commentId').val(null);
    } else {
        $('#commentSolicitudModalTitle').html('Responder comentario');
        $('#commentId').val(commentId);
    }
    $('#commentSolicitudModal').modal('show');
}

window.setPaymentMethod = function(method) {
    if (method == 1) { //card
        $('#cardPaymentContent').show();
        $('#payment-content').show();
        $('#submit').show();
        $('#transferPaymentContent').hide();
        $('#complete-payment-transfer').hide();
        $('#comprobante').removeAttr('required'); // quitar required
    } else {
        $('#cardPaymentContent').hide();
        $('#payment-content').hide();
        $('#submit').hide();
        $('#transferPaymentContent').show();
        $('#complete-payment-transfer').show();
        $('#comprobante').attr('required', 'required'); // poner required
    }
}

/* pago por clip */

if (document.getElementById('is_payment_card')) {
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
                    document.querySelector("#frm-payment").addEventListener("submit", async (event) => {
                        event.preventDefault();
                        try {

                            // Obtén el token de la tarjeta
                            const cardToken = await card.cardToken();

                            // Guarda el Card Token ID de la tarjeta en una constante
                            const cardTokenID = cardToken.id;
                            console.log("Card Token ID:", cardTokenID);
                            registerPaymentCardSolicitud(cardTokenID);

                        } catch (error) {

                            // Maneja errores durante la tokenización de la tarjeta
                            switch (error.code) {
                                case "CL2200":
                                case "CL2290":
                                    alert("Error: " + error.message);
                                    throw error;
                                    break;
                                case "AI1300":
                                    console.log("Error: ", error.message);
                                    break;
                                default:
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

        window.registerPaymentCardSolicitud = function(cardTokenID)
        {
            let solicitud_id = $('#solicitud_id').val();
            return axios.post('/admin/payment/card/save', {
                solicitud_id: solicitud_id,
                card_token_id: cardTokenID
            })
                .then(function (response) {
                    console.log('Payment registered successfully:', response.data);
                    Swal.fire({
                        title: '¡Registro Exitoso!',
                        html: 'El pago se ha procesado correctamente.',
                        icon: "success"
                    }).then((result) => {
                        // Acción después de cerrar el alerta
                        window.location = '/home';
                    });
                })
                .catch(function (error) {
                    console.error('Error registering payment:', error);
                    $('#errorPaymentModal').modal('show');
                    throw error;
                });
        }
    });

    $('#complete-payment-transfer').click(async function (e) {
        e.preventDefault();
      

        const form = document.getElementById("frm-payment");
        const data = new FormData(form);
        axios
            .post("/admin/payment/transfer/save", data)
            .then(function (response) {
                Swal.fire({
                    title: 'Verificación del comprobante de pago',
                    html: 'Nuestro equipo está revisando el comprobante de pago para proceder con la activación. En breve, recibirá un correo con la confirmación.',
                    icon: "success"
                }).then((result) => {
                    // Acción después de cerrar el alerta
                    window.location = '/home';
                });

            })
            .catch(e => { });
    });
}
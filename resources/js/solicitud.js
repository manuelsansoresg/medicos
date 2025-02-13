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

window.setSolicitud = function(getSolicitud)
{
    let solicitud = getSolicitud.value;
    let cantidadInput = document.getElementById("cantidad");
    $('#content-solicitud-pacientes').hide();
    // Establece el valor máximo del input en función de la solicitud
    if (solicitud == 1) {
        cantidadInput.setAttribute("max", "1");
    } 
    if(solicitud == 4) {
        $('#content-solicitud-pacientes').show();
    }
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
                text: 'Su solicitud ha sido enviada, favor de adjuntar el comprobante de pago en la siguiente ventana para su activación',
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
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

    // Establece el valor máximo del input en función de la solicitud
    if (solicitud == 1) {
        cantidadInput.setAttribute("max", "1");
    } else {
        cantidadInput.removeAttribute("max"); // Quita el atributo max si no es 1
    }
}

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
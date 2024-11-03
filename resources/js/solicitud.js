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


$("#frm-solicitud-comentario").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-solicitud-comentario");
    const data = new FormData(form);
    let solicitudId = $('#solicitudId').val();
    
    axios
        .post('/admin/solicitudes/'+solicitudId+'/comment/store', data)
        .then(function (response) {
            let result = response.data;
            
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
import Swal from 'sweetalert2'

window.deleteImagenEstudio = function(imagenId)
{

    Swal.fire({
        title: '¿Deseas borrar el elemento?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            axios
            .delete("/admin/estudio-imagenes/"+imagenId)
            .then(function (response) {
                window.location.reload();
            })
            .catch(error => { 
            });
        } 
      })
}
$("#frm-imagen-estudio").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-imagen-estudio");
    const data = new FormData(form);
    
    axios
        .post("/admin/estudio-imagenes", data)
        .then(function (result) {
            let estudioId        = $('#estudioId').val();
            let ConsultaAsignado = $('#ConsultaAsignado').val();
            let userCitaId       = $('#userCitaId').val();
            window.location = '/admin/estudio-imagenes/'+estudioId+''+'/'+userCitaId+'/'+ConsultaAsignado;
        })
        .catch(e => { });
});

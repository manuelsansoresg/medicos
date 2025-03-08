$("#frm-catalogo").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-catalogo");
    const data = new FormData(form);

    axios
        .post("/admin/catalogo", data)
        .then(function (response) {
            window.location = '/admin/catalogo';
        })
        .catch(e => { });
});

import Swal from 'sweetalert2'

window.deleteCatalogo = function(catalogId)
{
    Swal.fire({
        title: '¿Deseas borrar el elemento?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
      }).then((result) => {
        if (result.isConfirmed) {
            axios
            .delete("/admin/catalogo/"+catalogId)
            .then(function (response) {
                window.location = '/admin/catalogo';
            })
            .catch(error => { 
            });
        } 
      })
}
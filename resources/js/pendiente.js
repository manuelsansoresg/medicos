
import Swal from 'sweetalert2'

window.deletePendiente = function(pendiente_id)
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
            .delete("/admin/pendientes/"+pendiente_id)
            .then(function (response) {
                window.location = '/admin/pendientes';
            })
            .catch(error => { 
            });
        } 
      })
}
$("#frm-pendiente").submit(function (e) {
    e.preventDefault();

    const form = document.getElementById("frm-pendiente");
    const data = new FormData(form);
    
    axios
        .post("/admin/pendientes", data)
        .then(function (response) {
            window.location = '/admin/pendientes';
        })
        .catch(error => { 
            
        });
});


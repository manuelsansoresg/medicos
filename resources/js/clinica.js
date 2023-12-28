import Swal from 'sweetalert2'

window.deleteClinica = function(clinica_id)
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
            .delete("/admin/clinica/"+clinica_id)
            .then(function (response) {
                window.location = '/admin/clinica';
            })
            .catch(error => { 
            });
        } 
      })
}

$("#frm-clinica").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-clinica");
    const data = new FormData(form);
    
    axios
        .post("/admin/clinica", data)
        .then(function (response) {
            window.location = '/admin/clinica';
        })
        .catch(e => { });
});
import Swal from 'sweetalert2'

window.deleteConsultorio = function(consultorio_id)
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
            .delete("/admin/consultorio/"+consultorio_id)
            .then(function (response) {
                window.location = '/admin/consultorio';
            })
            .catch(error => { 
            });
        } 
      })
}

$("#frm-consultorio").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-consultorio");
    const data = new FormData(form);
    
    axios
        .post("/admin/consultorio", data)
        .then(function (response) {
            window.location = '/admin/consultorio';
        })
        .catch(e => { });
});
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


$("#frm-vincular-clinica").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-vincular-clinica");
    const data = new FormData(form);

    axios
        .post("/admin/clinica/vincular/store", data)
        .then(function (response) {
            let result = response.data;
            let msg = 'Vinculación realizada';
            if (result > 0) {
                msg = 'Ya existe una vinculación';
            }
            Swal.fire({
                title: msg,
                showDenyButton: false,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            })
            
        })
        .catch(e => { });
});
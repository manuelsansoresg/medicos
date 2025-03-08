$("#frm-paquete").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-paquete");
    const data = new FormData(form);

    axios
        .post("/admin/paquete", data)
        .then(function (response) {
            window.location = '/admin/paquete';
        })
        .catch(e => { });
});


window.deletePaquete = function(packageId)
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
            .delete("/admin/paquete/"+packageId)
            .then(function (response) {
                window.location = '/admin/paquete';
            })
            .catch(error => { 
            });
        } 
      })
}
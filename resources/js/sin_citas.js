
$("#frm-sincitas").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-sincitas");
    const data = new FormData(form);
    
    axios
        .post("/admin/sin_citas", data)
        .then(function (result) {
            window.location = '/admin/sin_citas';
        })
        .catch(e => { });
});


window.deleteSinCitas = function(id)
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
            .delete("/admin/sin_citas/"+id)
            .then(function (response) {
                window.location = '/admin/sin_citas';
            })
            .catch(error => { 
            });
        } 
      })
}
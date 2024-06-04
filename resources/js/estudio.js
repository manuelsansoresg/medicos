import Swal from 'sweetalert2'

window.deleteEstudio = function(estudio_id)
{
    let user_cita_id = $('#user_cita_id').val();
    let consultaAsignadoId  = $('#consultaAsignadoId').val();

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
            .delete("/admin/estudio/"+estudio_id)
            .then(function (response) {
                window.location = '/admin/consulta/'+user_cita_id+'/'+consultaAsignadoId+'/registro';
            })
            .catch(error => { 
            });
        } 
      })
}

$("#frm-estudio").submit(function (e) {
    e.preventDefault();
    let user_cita_id = $('#user_cita_id').val();
    let consultaAsignadoId  = $('#consultaAsignadoId').val();

    const form = document.getElementById("frm-estudio");
    const data = new FormData(form);
    
    axios
        .post("/admin/estudio", data)
        .then(function (response) {
            $('#content-estudio').hide();
            window.location = '/admin/consulta/'+user_cita_id+'/'+consultaAsignadoId+'/registro';
        })
        .catch(e => { });
});

window.editarEstudio = function(id)
{
    $('#content-estudio').show('slow');
    const element = document.getElementById("content-estudio");
    element.focus();

    axios
        .get("/admin/estudio/"+id)
        .then(function (response) {
            let result = response.data;
            $('#estudios').val(result.estudios);
            $('#diagnosticos').val(result.diagnosticos);
            $('#user_cita_id').val(result.user_cita_id);
            $('#paciente_id').val(result.paciente_id);
            $('#estudio_diagnostico_id').val(result.id);
           
        })
        .catch(e => { });
}

window.nuevoEstudio = function()
{
    $('#content-estudio').show('slow');
}

window.cancelEstudio = function()
{
    $('#content-estudio').hide();
}
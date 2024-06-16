import Swal from 'sweetalert2'

window.deleteConsulta = function(consultaId)
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
            .delete("/admin/consulta/"+consultaId)
            .then(function (response) {
                window.location = '/admin/consulta/'+user_cita_id+'/'+consultaAsignadoId+'/registro';
            })
            .catch(error => { 
            });
        } 
      })
}

$("#frm-consulta").submit(function (e) {
    e.preventDefault();
    let user_cita_id = $('#user_cita_id_origin').val();
    let consultaAsignadoId  = $('#consultaAsignadoId').val();

    const form = document.getElementById("frm-consulta");
    const data = new FormData(form);
    
    axios
        .post("/admin/consulta", data)
        .then(function (response) {
            $('#content-consulta').hide();
            window.location = '/admin/consulta/'+user_cita_id+'/'+consultaAsignadoId+'/registro';
        })
        .catch(e => { });
});

window.editarConsulta = function(id)
{
    $('#content-consulta').show('slow');
    const element = document.getElementById("content-consulta");
    element.focus();

    axios
        .get("/admin/consulta/"+id)
        .then(function (response) {
            let result = response.data;
            $('#motivo').val(result.motivo);
            $('#peso').val(result.peso);
            $('#exploracion').val(result.exploracion);
            $('#temperatura').val(result.temperatura);
            $('#estatura').val(result.estatura);
            $('#receta').val(result.receta);
            $('#user_cita_id').val(result.user_cita_id);
            $('#paciente_id').val(result.paciente_id);
            $('#consulta_id').val(result.id);
            $('#indicaciones_generales').val(result.indicaciones_generales);
           
        })
        .catch(e => { });
}

window.nuevaConsulta = function(peso, temperatura, estatura)
{
    $('#peso').val(peso);
    $('#temperatura').val(temperatura);
    $('#estatura').val(estatura);
    $('#content-consulta').show('slow');
}

window.cancelConsulta = function()
{
    $('#content-consulta').hide();
}
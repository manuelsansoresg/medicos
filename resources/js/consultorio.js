import Swal from 'sweetalert2'

window.deleteConsultorio = function (consultorio_id) {
    Swal.fire({
        title: '¿Deseas borrar el elemento?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
    }).then((result) => {
        if (result.isConfirmed) {
            axios
                .delete("/admin/consultorio/" + consultorio_id)
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


//horario consulta

window.changeOffice = function (office, userId) {
    $('#content-horario-consulta').html('');
    $('#content-duracion-consulta').hide();
    axios
        .get("/admin/consultorio/" + office+'/'+userId+'/show')
        .then(function (response) {
            let result = response.data;
            $('#content-horario-consulta').html(result);
            $('#content-duracion-consulta').show();
        })
        .catch(e => { });
}


$("#frm-vincular-consultorio").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-vincular-consultorio");
    const data = new FormData(form);

    axios
        .post("/admin/consultorio/vincular/store", data)
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


window.validateHours = function(id) {
    var startHourSelect = $('#'+id+'_ini').val();
    var endHourSelect = $('#'+id+'_fin').val();
    console.log(id);

    if (endHourSelect> 0 && (startHourSelect && endHourSelect)) {
        var startHour = parseInt(startHourSelect, 10);
        var endHour = parseInt(endHourSelect, 10);
        console.log(startHour, endHour);
        if (startHour >= endHour) {
            Swal.fire({
                title: 'Error!',
                text: 'La hora final debe ser mayor a la hora final.',
                icon: 'error',
              })
            $('#btn-add-office-user').prop('disabled', true);
        }else {
            // Si todo está bien, habilitamos el botón
            $('#btn-add-office-user').prop('disabled', false);
        }
        
    } else {
        console.error("No se encontraron los elementos de hora de inicio o fin");
    }
}

function sendFormData() {
    // Obtener los datos del formulario
    var formData = new FormData($('#frm-user-add-office')[0]);
    let userId = $('#userId').val();
    // Enviar los datos con Axios
    axios.post('/admin/consulta-asignado', formData)
        .then(function(response) {
            // Manejar la respuesta exitosa
            Swal.fire({
                title: 'Datos guardados exitosamente',
                text:'¿Deseas volver al listado de horarios?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'SÍ',
                denyButtonText: `NO`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '/admin/consulta-asignado/'+userId;
                }
            })
        })
        .catch(function(error) {
            // Manejar el error
            console.error(error);
        });
}


$('#frm-user-add-office').submit(function(e) {
    e.preventDefault();
    sendFormData();
});

document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('idConsultorio')) {
        let offices =  $('#offices').val();
        let userId =  $('#userId').val();
        changeOffice(offices, userId);
    }
});

window.deleteConsultaAsignado = function (userId , consultorioId) {
    Swal.fire({
        title: '¿Deseas borrar el elemento?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
    }).then((result) => {
        if (result.isConfirmed) {
            axios
                .delete("/admin/consulta-asignado/"+userId+"/"+consultorioId+"/delete")
                .then(function (response) {
                    window.location = '/admin/consulta-asignado/'+userId;
                })
                .catch(error => {
                });
        }
    })
}
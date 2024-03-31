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

window.changeOffice = function (office) {
    $('#content-horario-consulta').html('');
    $('#content-duracion-consulta').hide();
    axios
        .get("/admin/consultorio/" + office)
        .then(function (response) {
            let result = response.data;
            $('#content-horario-consulta').html(result);
            $('#content-duracion-consulta').show();
        })
        .catch(e => { });
}


window.asignarconsult = function () {

  


};



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

    // Enviar los datos con Axios
    axios.post('/admin/consulta-asignado', formData)
        .then(function(response) {
            // Manejar la respuesta exitosa
            console.log(response.data);
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

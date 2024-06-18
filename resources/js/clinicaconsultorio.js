if (!document.getElementById('isRedirect')) {
    axios
        .get("/query/clinicaYConsultorio")
        .then(function (response) {
            let result = response.data;
            if (result.status == 500) {
                window.location = '/query/viewClinicaYConsultorio';
            }
            /* if (result.status == 600) {
                Swal.fire({
                    title: "Sin consultorio asignado",
                    text: "Si no es usuario doctor pedir que le asignen su consultorio",
                    icon: "warning"
                  }).then((result) => {
                    });
            } */
        })
        .catch(error => {
        });
}

if (document.getElementById('setClinica')) {
    axios
        .get("/admin/clinica/consultorio/myConfiguration")
        .then(function (response) {
            let result = response.data;
            let clinica = result.clinica;
            let consultorio = result.consultorio;
        })
        .catch(error => {
        });
}

window.changeConsultorio = function () {
    let clinica = $('#setClinica').val();
    let selectConsultorio = document.getElementById('setConsultorio');

    // Limpiar el select excepto la primera opción
    selectConsultorio.options.length = 1;

    axios
        .get("/admin/clinica/" + clinica + "/consultorio/get")
        .then(function (response) {
            let result = response.data;

            if (result.length > 0) {
                // Desbloquear el select
                selectConsultorio.disabled = false;

                // Llenar el select con los datos obtenidos
                result.forEach(function (consultorio) {
                    let option = document.createElement('option');
                    option.value = consultorio.idconsultorios;
                    option.text = consultorio.vnumconsultorio;
                    selectConsultorio.add(option);
                });
                let optionTodos = new Option('Todos', 0);
                selectConsultorio.add(optionTodos)

            }
        })
        .catch(error => {
            // Manejar errores si es necesario
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const selectClinica = document.getElementById('setClinica');
    const selectConsultorio = document.getElementById('setConsultorio');

    selectClinica.addEventListener('change', function() {
        if (selectClinica.value) {
            selectConsultorio.removeAttribute('disabled');
            // Aquí puedes agregar la lógica para cargar los consultorios asociados a la clínica seleccionada
        } else {
            selectConsultorio.setAttribute('disabled', 'disabled');
            selectConsultorio.value = "";
        }
    });

    if (document.getElementById('frm-selection')) {
        document.getElementById('frm-selection').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

            // Validar si ambos selects tienen un valor seleccionado
            if (!selectClinica.value || !selectConsultorio.value) {
                Swal.fire({
                    title: "Sin consultorio asignado",
                    text: "Pedirle al usuario administrador que le asigne un consultorio",
                    icon: "warning"
                });
                return; // No continuar con el envío del formulario
            }

            // Obtener el formulario y sus valores
            let formulario = event.target;
            let formData = new FormData(formulario);

            // Realizar la solicitud POST utilizando Axios
            axios.post('/admin/clinica/consultorio/set', formData)
                .then(function(response) {
                    // Manejar la respuesta si es necesario
                    Swal.fire({
                        text: "Los valores se han almacenado con éxito. A partir de ahora, los filtros en todo el panel administrativo estarán basados en esta selección.",
                        icon: "warning"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '/home';
                        }
                    });
                })
                .catch(function(error) {
                    // Manejar errores si es necesario
                    console.error(error);
                });
        });
    }
});



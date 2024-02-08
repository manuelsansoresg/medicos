if (!document.getElementById('isRedirect')) {
    axios
        .get("/query/clinicaYConsultorio")
        .then(function (response) {
            let result = response.data;
            if (result.status == 500) {
                window.location = '/query/viewClinicaYConsultorio';
            }
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
            } else {
                // Si no hay datos, bloquear el select nuevamente
                selectConsultorio.disabled = true;
            }
        })
        .catch(error => {
            // Manejar errores si es necesario
        });
}

if (document.getElementById('frm-selection')) {
    document.getElementById('frm-selection').addEventListener('submit', function (event) {
        event.preventDefault();  // Prevenir el envío normal del formulario
    
        // Obtener el formulario y sus valores
        let formulario = event.target;
        let formData = new FormData(formulario);
    
        // Realizar la solicitud POST utilizando Axios
        axios.post('/admin/clinica/consultorio/set', formData)
            .then(function (response) {
                // Manejar la respuesta si es necesario
                Swal.fire({
                    text: "Los valores se han almacenado con éxito. A partir de ahora, los filtros en todo el panel administrativo estarán basados en esta selección.",
                    icon: "warning"
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      window.location = '/home';
                    }});
            })
            .catch(function (error) {
                // Manejar errores si es necesario
                console.error(error);
            });
    });
}


import Swal from 'sweetalert2'

window.deleteUser = function(user_id)
{
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
            .delete("/admin/usuarios/"+user_id)
            .then(function (response) {
                window.location = '/admin/usuarios';
            })
            .catch(error => { 
            });
        } 
      })
}

$("#frm-users").submit(function (e) {
    e.preventDefault();

    const form = document.getElementById("frm-users");
    const data = new FormData(form);
    
    axios
        .post("/admin/usuarios", data)
        .then(function (response) {
            window.location = '/admin/usuarios';
        })
        .catch(error => { 
            if (error.response) {
                // The request was made and the server responded with an error
                if (error.response.status === 422) {
                    // Validation error
                    const errors = error.response.data.errors;
                    if (errors && errors['data.email']) {
                        // Check if the error is related to data.email
                        const errorMessage = errors['data.email'][0];
                        if (errorMessage === "El valor del campo data.email ya está en uso.") {
                            // Display an alert
                            Swal.fire({
                                title: 'Error!',
                                text: 'El valor del correo electrónico ya está en uso.',
                                icon: 'error',
                              })
                        }
                    }
                }
            } else {
                // Error without a response (e.g., network error)
                //console.error(error);
            }
        });
});

$("#frm-paciente").submit(function (e) {
    e.preventDefault();

    const form = document.getElementById("frm-paciente");
    const data = new FormData(form);
    
    axios
        .post("/admin/pacientes", data)
        .then(function (response) {
            window.location = '/admin/pacientes';
        })
        .catch(error => { 
            if (error.response) {
                // The request was made and the server responded with an error
                if (error.response.status === 422) {
                    // Validation error
                    const errors = error.response.data.errors;
                    if (errors && errors['data.email']) {
                        // Check if the error is related to data.email
                        const errorMessage = errors['data.email'][0];
                        if (errorMessage === "El valor del campo data.email ya está en uso.") {
                            // Display an alert
                            Swal.fire({
                                title: 'Error!',
                                text: 'El valor del correo electrónico ya está en uso.',
                                icon: 'error',
                              })
                        }
                    }
                }
            } else {
                // Error without a response (e.g., network error)
                //console.error(error);
            }
        });
});

window.addPrincipalUser = function(puesto)
{
    let rol = puesto.value;
    $('#usuario_propietario').hide();
    if (rol == 'auxiliar' || rol == 'secretario') {
        $('#usuario_propietario').show();
    }
   
}

window.deletePaciente = function(user_id)
{
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
            .delete("/admin/pacientes/"+user_id)
            .then(function (response) {
                window.location = '/admin/pacientes';
            })
            .catch(error => { 
            });
        } 
      })
}
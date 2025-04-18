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

$("#frm-vincular-usuario").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-vincular-usuario");
    const data = new FormData(form);

    axios
        .post("/admin/usuarios/vincular/store", data)
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


window.previewImage = function(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).html(`
                <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteImage('${input.id}')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            `);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

window.deleteImage = function(type, user_id) {
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
            .delete("/admin/usuarios/ine/"+type+'/delete')
            .then(function (response) {
                window.location.reload();
            })
            .catch(error => { 
            });
        } 
      })
}

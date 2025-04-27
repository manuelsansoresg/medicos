if($('#paciente-curp').length > 0 && !document.getElementById('usuario_principal')){
    $('#paciente-curp').on('change', function(){
        console.log($(this).val());
        $.ajax({
            url: '/admin/paciente/curp',
            type: 'POST',
            data: {curp: $(this).val()},
            success: function(response){
                let data = response.data;
                let error = response.error;
                
                if(error == false){
                    $('#btn-vincular-paciente').show();
                    $('#btn-guardar-paciente').hide();
                    Swal.fire({
                        title: 'Vincular paciente',
                        text: "¿El paciente ya existe, desea vincularlo?",
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'SÍ',
                        denyButtonText: `NO`,
                      }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            $('#paciente-nombre').val(data.name);
                            $('#paciente-apellido').val(data.vapellido);
                            $('#paciente-segundo-apellido').val(data.segundo_apellido);
                            $('#fecha_nacimiento').val(data.fecha_nacimiento);
                            $('#telefono').val(data.ttelefono);
                            $('#direccion').val(data.tdireccion);
                            $('#num_seguro').val(data.num_seguro);
                            
                            $('#content-status').hide();
                            $('#content-data-system').hide();
                            $('#user_id').val(data.id);


                            const form = document.getElementById('frm-paciente');
                            // Verificar que el formulario existe
                            if (form) {
                            // Obtener todos los elementos de entrada dentro del formulario
                            const inputs = form.querySelectorAll('input, textarea, select');
                            
                            // Establecer cada elemento como readonly
                            inputs.forEach(input => {
                                if (input.type === 'checkbox' || input.type === 'radio' || input.tagName === 'SELECT') {
                                // Para checkboxes, radios y selects, usamos disabled ya que readonly no funciona bien
                                input.disabled = true;
                                } else {
                                // Para inputs de texto, número, email, etc. y textareas
                                input.disabled = true;
                                }
                            });
                            }
                        } 
                      })

                    
                } else {
                    $('#btn-guardar-paciente').show();
                    $('#btn-vincular-paciente').hide();
                    $('#content-status').show();
                    $('#content-data-system').show();
                }
            }
        });
    });
}

window.vincularPaciente = function()
{
    axios.post('/admin/paciente/vincular', {
        user_id: $('#user_id').val()
    }).then(function(response){
        window.location.href = '/admin/pacientes';
    });
}

window.deleteVinculo = function(user_id)
{
    Swal.fire({
        title: '¿Deseas borrar el vinculo?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            axios.post('/admin/paciente/delete-vinculo', {
                user_id: user_id
            }).then(function(response){
                window.location.href = '/admin/pacientes';
            });
        } 
      })

    
}	

window.permisosPaciente = function(user_id)
{

}
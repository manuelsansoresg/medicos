$(document).ready(function () {
    // Captura el evento de cambio del select
    $('#InputFecha').on('change', function () {
        // Envía el formulario al cambiar el valor del select
        $('#frm-fecha').submit();
    });

    window.addCita = function (idconsultasignado)
    {
        /* $('#idconsultasignado').val(idconsultasignado);
        $('#modalCita').modal('show'); */
    }

    $('#selectPaciente').select2({
        placeholder: "Escribe para buscar..",
        minimumInputLength: 3,
        ajax: {
          url: '/admin/pacientes/get/search',
          data: function (params) {
            var query = {
              search: params.term,
            }
      
            // Query parameters will be ?search=[term]&type=public
            return query;
          }
        }
      });

      window.setParamAddCita = function (id, hora)
      {
        let fecha = $('#InputFecha').val();
        window.location = "/admin/citas/"+id+"/"+hora+"/"+fecha+"/add"
      }

      if (document.getElementById('frm-cita')) {
        document.getElementById('frm-cita').addEventListener('submit', function (event) {
            event.preventDefault();  // Prevenir el envío normal del formulario
        
            // Obtener el formulario y sus valores
            let formulario = event.target;
            let formData = new FormData(formulario);
            
            // Realizar la solicitud POST utilizando Axios
            axios.post('/admin/citas', formData)
                .then(function (response) {
                  
                })
                .catch(function (error) {
                    // Manejar errores si es necesario
                    console.error(error);
                });
        });
    }

});
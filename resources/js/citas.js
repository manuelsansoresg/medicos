$(document).ready(function () {
  // Captura el evento de cambio del select
  $('#InputFecha').on('change', function () {
    // Envía el formulario al cambiar el valor del select
    //$('#frm-fecha').submit();
    consultCita();
  });

  if (document.getElementById('InputFecha')) {
    consultCita();
  }

  function consultCita() {
    let InputFecha = $('#InputFecha').val();
    axios.get('/admin/citas/' + InputFecha)
      .then(function (response) {
        let result = response.data;
      })
      .catch(function (error) {
        // Manejar errores si es necesario
        console.error(error);
      });
  }

  window.addCita = function (idconsultasignado) {
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

  window.setParamAddCita = function (id, hora) {
    let fecha = $('#InputFecha').val();
    $('#content-hoursCita').hide();
    $('#content-form').show();
    $('#hora').val(hora);
    $('#consulta_asignado_id').val(id);
    $('#horaSeleccionada').html(hora);
    //window.location = "/admin/citas/" + id + "/" + hora + "/" + fecha + "/add"
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
          window.location.reload();
        })
        .catch(function (error) {
          // Manejar errores si es necesario
          console.error(error);
        });
    });
  }

});

window.setCita = function () {
  
  let iddoctor  = $('#iddoctor').val();
  let valueDate = $('#InputFecha').val();

  $('#content-hoursCita').html('');
  $('#content-paciente-livewire').hide();
  
  axios.get('/admin/citas/'+valueDate+'/'+iddoctor+'/set')
    .then(function (response) {
      let result = response.data;
      let view = result.view;
      let data = result.data;
      if (data['totalConsulta'] > 0 ) {
        $('#content-paciente-livewire').show();
      }
      $('#content-hoursCita').show();
      $('#content-hoursCita').html(view);
    })
    .catch(function (error) {
      // Manejar errores si es necesario
      console.error(error);
    });
}

window.liberarCita = function(id)
{
  Swal.fire({
    title: '¿Deseas liberar la cita?',
    showDenyButton: true,
    showCancelButton: false,
    confirmButtonText: 'SÍ',
    denyButtonText: `NO`,
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete('/admin/citas/'+id)
      .then(function (response) {
        window.location.reload();
      })
      .catch(function (error) {
        // Manejar errores si es necesario
        console.error(error);
      });
    } 
  })
  
  
}

window.SelecPaciente = function (pacienteId, nombre) {
  $('#content-paciente-add').show('slow');
  $('#paciente_id').val(pacienteId);
  $('#paciente-add').html(nombre);
  $('#content-paciente-livewire').hide('slow');
}

window.changePacienteCita = function()
{
  $('#content-paciente-livewire').show('slow');
  $('#content-paciente-add').hide('slow');
}

if (document.getElementById('frm-cita')) {
  window.addEventListener('DOMContentLoaded', function() {
    setCita();
  });
}

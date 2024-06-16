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

      // Obtener el valor del input con id 'hora'
      var hora = document.getElementById("hora").value;

      // Verificar si el valor está vacío
      if (!hora) {
          // Si está vacío, mostrar una alerta
          Swal.fire({
            text: "Debe seleccionar una hora.",
            icon: "warning"
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            });
      } else {

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
      }

    });
  }

});

window.setCita = function () {
  
  let iddoctor  = $('#iddoctor').val();
  let valueDate = $('#InputFecha').val();

  $('#content-hoursCita').html('');
  
  
  axios.get('/admin/citas/'+valueDate+'/'+iddoctor+'/set')
    .then(function (response) {
      let result = response.data;
      let view = result.view;
      let data = result.data;
      
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
}

window.changePacienteCita = function()
{
  $('#content-paciente-add').hide('slow');
}

if (document.getElementById('frm-cita')) {
  window.addEventListener('DOMContentLoaded', function() {
    setCita();
  });
}


$("#busqueda-pacientes").easyAutocomplete({
  maxResults: 50,
  url: function (search) {
      return "/admin/pacientes/get/search?search=" + search;
  },

  getValue: function (element) {
      return element.id+'-'+element.name+' '+element.vapellido;
  },
  list: {
      maxNumberOfElements: 30,
      onClickEvent: function(){
          let folio = $('#busqueda-pacientes').val().split('-')[0];
          $('#paciente_id').val(folio);
      },
     
      onHideListEvent: function () {
          let folio = $('#busqueda-pacientes').val().split('-')[0];
          $('#paciente_id').val(folio);
      }
  }
   
});

window.addUserCita = function(consultaAsignadoId, hora)
{
  $('#consulta_asignado_id').val(consultaAsignadoId);
  $('#hora').val(hora);
  $('#addUser').modal('show');
}

window.updateSelectedTab = function (selectedTab)
{
  $('#content-nuevo-estudio').hide();
  if (selectedTab == 'estudios') {
    $('#content-nuevo-estudio').show();
  }
  Livewire.emit('updateSelectedTab', selectedTab);
}


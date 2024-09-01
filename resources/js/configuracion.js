import Swal from 'sweetalert2'

window.deletePlantilla = function(templateId)
{
    Swal.fire({
        title: '¿Deseas borrar el elemento?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'SÍ',
        denyButtonText: `NO`,
      }).then((result) => {
        if (result.isConfirmed) {
            axios
            .get("/admin/template-formulario/"+templateId+'/delete')
            .then(function (response) {
                window.location = '/admin/template-formulario'; 
            })
            .catch(error => { 
            });
        } 
      })
}

document.addEventListener('DOMContentLoaded', function () {
  // Función para inicializar la visibilidad del campo de opciones
  function initializeFieldOptions() {
      const fields = document.querySelectorAll('.field');

      fields.forEach(function (field) {
          const fieldTypeSelect = field.querySelector('.field-type');
          const fieldOptionsDiv = field.querySelector('.field-options');

          // Lógica para mostrar u ocultar las opciones basado en el tipo seleccionado
          toggleOptionsVisibility(fieldTypeSelect, fieldOptionsDiv);

          // Añadir evento change para actualizar la visibilidad en tiempo real
          fieldTypeSelect.addEventListener('change', function () {
              toggleOptionsVisibility(fieldTypeSelect, fieldOptionsDiv);
          });
      });
  }

  // Función para mostrar/ocultar el campo de opciones basado en el valor del select
  function toggleOptionsVisibility(fieldTypeSelect, fieldOptionsDiv) {
      if (fieldTypeSelect.value === 'select') {
          fieldOptionsDiv.style.display = 'block';
      } else {
          fieldOptionsDiv.style.display = 'none';
      }
  }

  // Inicializar los campos ya existentes
  initializeFieldOptions();

document.getElementById('add-field').addEventListener('click', function () {
  const fields = document.getElementById('fields');
  const fieldCount = fields.getElementsByClassName('field').length;

  const newField = document.createElement('div');
  newField.classList.add('field');
  newField.innerHTML = `
      <label for="fields[${fieldCount}][name]">Nombre del Campo</label>
      <input class="form-control" type="text" name="fields[${fieldCount}][name]" required>

      <label for="fields[${fieldCount}][type]">Tipo de Campo</label>
      <select class="form-control field-type" name="fields[${fieldCount}][type]" required>
          <option value="text">Texto</option>
          <option value="date">Fecha</option>
          <option value="textarea">Área de Texto</option>
          <option value="select">Seleccionar</option>
          <option value="image">Imagen</option>
      </select>

      <label for="fields[${fieldCount}][is_required]">¿Es Obligatorio?</label>
      <input type="checkbox" name="fields[${fieldCount}][is_required]">

      <div class="col-12 field-options" style="display:none;">
          <label for="fields[${fieldCount}][options]">Opciones</label>
          <textarea class="form-control" name="fields[${fieldCount}][options]"></textarea>
      </div>
      <div class="col-12">
      <button type="button" class="btn btn-danger mt-2 remove-field">Eliminar campo</button>
      </div>
  `;

  fields.appendChild(newField);

  // Agrega evento change al select para mostrar/ocultar las opciones
  const fieldTypeSelect = newField.querySelector('.field-type');
  const fieldOptionsDiv = newField.querySelector('.field-options');

  fieldTypeSelect.addEventListener('change', function () {
      if (fieldTypeSelect.value === 'select') {
          fieldOptionsDiv.style.display = 'block';
      } else {
          fieldOptionsDiv.style.display = 'none';
      }
  });

  // Agrega evento click al botón "Eliminar"
  newField.querySelector('.remove-field').addEventListener('click', function () {
      newField.remove();
  });
});
});
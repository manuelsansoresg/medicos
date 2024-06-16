

require('./bootstrap');


require('./sin_citas');
require('./clinica');
require('./consultorio');
require('./clinicaconsultorio');
require('./user');
require('./citas');
require('./access');
require('./pendiente');
require('./consulta');
require('./estudio');

document.addEventListener('DOMContentLoaded', function() {
    $('.select2multiple').select2({
        placeholder: "Escribe para buscar.."
    });
});
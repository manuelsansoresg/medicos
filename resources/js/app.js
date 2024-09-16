

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
require('./imagenEstudio');
require('./configuracion');
require('./config_download_expedient');
require('./expedient');

document.addEventListener('DOMContentLoaded', function() {
    $('.select2multiple').select2({
        placeholder: "Escribe para buscar..",
          width: '100%'
    });
});
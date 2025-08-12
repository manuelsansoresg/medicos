

require('./bootstrap');


require('./sin_citas');
require('./clinica');
require('./consultorio');
require('./catalogo');
require('./paquete');
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
require('./solicitud');
require('./setting');
require('./horarios');
require('./biencomun');


/* require('./configurar_entorno'); */

document.addEventListener('DOMContentLoaded', function() {
    $('.select2multiple').select2({
        placeholder: "Escribe para buscar..",
          width: '100%'
    });
});

document.addEventListener('file-download', event => {
    const url = event.detail.url;
    const filePath = event.detail.filePath;

    // Crear un elemento <a> para descargar el archivo
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.target = '_blank';
    anchor.download = url.split('/').pop(); // Nombre del archivo
    anchor.click();

    // Enviar una solicitud al servidor para eliminar el archivo después de descargarlo
    fetch('/delete-temp-file', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ filePath })
    });
});
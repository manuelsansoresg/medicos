
if (document.getElementById('selectAll')) {
}
// Función para habilitar/deshabilitar el botón según la selección de los checkboxes
function toggleDownloadButton() {
    let checkboxes = document.querySelectorAll('.selectExpedient');
    let btnDownload = document.getElementById('btn-download-expedient');
    
    // Verificar si al menos un checkbox está seleccionado
    let isAnySelected = Array.from(checkboxes).some(checkbox => checkbox.checked);
    
    // Si hay al menos uno seleccionado, quitamos la clase 'disabled', si no, la añadimos
    if (isAnySelected) {
        btnDownload.classList.remove('disabled');
    } else {
        btnDownload.classList.add('disabled');
    }
}

// Escuchar el evento 'change' en el checkbox 'selectAll'
document.getElementById('selectAll').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.selectExpedient');
    
    // Marcar o desmarcar todos según el estado de selectAll
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = document.getElementById('selectAll').checked;
    });

    // Llamar a la función para habilitar/deshabilitar el botón
    toggleDownloadButton();
});

// Escuchar el evento 'change' en cada checkbox individual
document.querySelectorAll('.selectExpedient').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        // Llamar a la función para habilitar/deshabilitar el botón
        toggleDownloadButton();
    });
});




$("#frm-download-expedient").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-download-expedient");
    const data = new FormData(form);

    axios({
        method: 'post',
        url: '/admin/expedientes/select/download',
        data: data,
        responseType: 'blob', // Importante: indicamos que la respuesta es un blob (archivo binario)
    })
    .then(function (response) {
        // Crear un enlace para forzar la descarga
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;

        // Establecer el nombre del archivo (puede ser el que envías desde Laravel)
        link.setAttribute('download', 'expedientes.zip'); // Nombre del archivo a descargar

        // Añadir el enlace al cuerpo del documento y forzar el clic
        document.body.appendChild(link);
        link.click();

        // Eliminar el enlace después de descargar
        document.body.removeChild(link);
    })
    .catch(function (error) {
        console.error("Error al descargar el archivo", error);
    });
});
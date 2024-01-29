$(document).ready(function () {
    // Captura el evento de cambio del select
    $('#InputFecha').on('change', function () {
        // Envía el formulario al cambiar el valor del select
        $('#frm-fecha').submit();
    });
});
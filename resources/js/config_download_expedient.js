window.configDownloads = function()
{

    const form = document.getElementById("frm-config-download-expedient");
    const data = new FormData(form);
    
    axios
        .post("/admin/configuracion-descargas", data)
        .then(function (response) {
            
        })
        .catch(e => { });
}

if (document.getElementById('consulta1')) {
    document.querySelectorAll('input[type=radio]').forEach(function(radio) {
        radio.addEventListener('click', function() {
            configDownloads(); // Llama a la función cuando se hace clic en un radio
        });
    });
}
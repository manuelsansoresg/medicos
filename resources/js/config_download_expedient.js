$("#frm-config-download-expedient").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-config-download-expedient");
    const data = new FormData(form);
    
    axios
        .post("/admin/configuracion-descargas", data)
        .then(function (response) {
            
        })
        .catch(e => { });
});
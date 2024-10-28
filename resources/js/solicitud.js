$("#frm-solicitud").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-solicitud");
    const data = new FormData(form);
    
    axios
        .post("/admin/solicitudes", data)
        .then(function (response) {
            let result = response.data;
            window.location = '/admin/solicitudes/'+result.id;
        })
        .catch(e => { });
});
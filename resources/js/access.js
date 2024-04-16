$("#frm-access").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-access");
    const data = new FormData(form);
    
    axios
        .post("/admin/acceso", data)
        .then(function (result) {
            window.location = '/admin/acceso';
        })
        .catch(e => { });
});
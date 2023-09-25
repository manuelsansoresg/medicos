$("#frm-clinica").submit(function (e) {
    e.preventDefault();

    const form = document.getElementById("frm-clinica");
    const data = new FormData(form);
    
    axios
        .post("/admin/clinica", data)
        .then(function (response) {
            window.location = '/admin/clinica';
        })
        .catch(e => { });
});
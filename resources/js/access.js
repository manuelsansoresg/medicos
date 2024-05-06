$("#frm-access").submit(function (e) {
    e.preventDefault();
    const form = document.getElementById("frm-access");
    const data = new FormData(form);
    
    axios
        .post("/admin/acceso", data)
        .then(function (response) {
            window.location = '/admin/acceso';
        })
        .catch(function (error) {
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                // Recorre los errores y muestra cada uno debajo del campo correspondiente
                for (let field in errors) {
                    const errorMessage = errors[field][0];
                    // Mostrar mensaje de error debajo del select
                    const errorDivId = 'error-' + field.replace('.', '_'); // Reemplaza "." por "_" en el nombre del campo
                    console.log(errorDivId);
                    $('#'+errorDivId).html(errorMessage);
                    
                }
            }
        });
});
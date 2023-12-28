$('.select2multiple').select2({
    placeholder: "Escribe para buscar..",
});

require('./bootstrap');


require('./clinica');
require('./consultorio');
require('./user');

if (!document.getElementById('isRedirect')) {
    axios
        .get("/query/clinicaYConsultorio")
        .then(function (response) {
            let result = response.data;
            if (result.status == 500) {
                /* window.location = '/query/viewClinicaYConsultorio'; */
            }
        })
        .catch(error => {
        });
}
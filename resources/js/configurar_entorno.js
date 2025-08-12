import Swal from 'sweetalert2';
import axios from 'axios';

// Escuchar el evento de configuración finalizada
window.addEventListener('configuracion-finalizada', function() {
    Swal.fire({
        icon: 'info',
        title: 'Configuración exitosa',
        text: 'Le recuerdo que en su pagina administrativa podra tener acceso al wizard de nuevo.',
        showCancelButton: false,
        confirmButtonText: 'Finalizar configuración',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/';
        }
    });
});


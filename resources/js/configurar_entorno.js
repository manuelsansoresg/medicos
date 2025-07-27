import Swal from 'sweetalert2';
import axios from 'axios';

// Configurar CSRF para todas las peticiones AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    let clinicaId = null;
    let consultorioId = null;
    let horariosConfigurados = false;
    let currentStep = 1;
    let totalSteps = 0;

    // Variables para almacenar datos de los formularios
    let datosClinica = {};
    let datosConsultorio = {};
    let datosHorarios = {};

    // Configurar el wizard según el tipo de configuración
    function initializeWizard() {
        const typeConfig = window.typeConfiguration;
        
        if (typeConfig === 1) {
            // Flujo: Clínica -> Consultorio -> Horarios
            totalSteps = 3;
            showStep(1);
        } else if (typeConfig === 2) {
            // Flujo: Consultorio -> Horarios
            totalSteps = 2;
            showStep(1);
        }
        
        updateStepIndicators();
    }

    // Mostrar el paso específico
    function showStep(stepNumber) {
        $('.step-pane').removeClass('active');
        $(`#step${stepNumber}`).addClass('active');
        currentStep = stepNumber;
        updateStepIndicators();
        
        // Cargar datos específicos del paso si es necesario
        if (stepNumber === totalSteps) {
            loadHorariosData();
        }
    }

    // Actualizar indicadores de pasos
    function updateStepIndicators() {
        $('.step').removeClass('active completed');
        
        for (let i = 1; i <= totalSteps; i++) {
            const stepElement = $(`#step${i}-indicator`);
            if (i < currentStep) {
                stepElement.addClass('completed');
            } else if (i === currentStep) {
                stepElement.addClass('active');
            }
        }
    }

    // Navegación entre pasos
    $('#next-to-step2').click(async function() {
        const typeConfig = window.typeConfiguration;
        
        if (typeConfig === 1) {
            // Validar formulario de clínica
            if (!validateClinicaForm()) return;
            await saveOrUpdateClinica();
            showStep(2);
        } else if (typeConfig === 2) {
            // Validar formulario de consultorio
            if (!validateConsultorioForm()) return;
            await saveOrUpdateConsultorio();
            showStep(2);
        }
    });

    $('#next-to-step3').click(async function() {
        // Solo para type_configuration = 1
        if (!validateConsultorioForm()) return;
        await saveOrUpdateConsultorio();
        showStep(3);
    });

    // Botones de regreso
    $('#back-to-step1').click(function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    $('#back-to-step2').click(function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Finalizar configuración
    $('#finalizar-configuracion').click(function() {
        finalizarConfiguracion();
    });

    // Validaciones de formularios
    function validateClinicaForm() {
        const nombre = $('#inputNombreClinica').val();
        const folio = $('#inputFolioClinica').val();
        const estatus = $('#inputEstatusClinica').val();

        if (!nombre || !folio || !estatus) {
            Swal.fire({
                icon: 'error',
                title: 'Campos requeridos',
                text: 'Por favor complete todos los campos obligatorios de la clínica.'
            });
            return false;
        }

        // Guardar datos de clínica
        datosClinica = {
            tnombre: nombre,
            tdireccion: $('#inputDireccionClinica').val(),
            vrfc: $('#inputRfcClinica').val(),
            ttelefono: $('#inputTelefonoClinica').val(),
            vfolioclinica: folio,
            istatus: estatus
        };

        return true;
    }

    function validateConsultorioForm() {
        const nombre = $('#inputNombreConsultorio').val();

        if (!nombre) {
            Swal.fire({
                icon: 'error',
                title: 'Campo requerido',
                text: 'Por favor ingrese el nombre del consultorio.'
            });
            return false;
        }

        // Guardar datos de consultorio
        datosConsultorio = {
            vnumconsultorio: nombre,
            thubicacion: $('#inputUbicacionConsultorio').val(),
            ttelefono: $('#inputTelefonoConsultorio').val()
        };

        return true;
    }

    function validateHorariosForm() {
        const typeConfig = window.typeConfiguration;
        let clinica = null;
        let consultorio = null;
        
        if (typeConfig === 1) {
            clinica = $('#clinica-wizard').val() || null;
            consultorio = $('#offices-wizard').val() || null;
            
            if (!clinica || !consultorio) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos requeridos',
                    text: 'Por favor seleccione una clínica y un consultorio.'
                });
                return false;
            }
        } else if (typeConfig === 2) {
            consultorio = $('#offices-wizard').val() || null;
            
            if (!consultorio) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo requerido',
                    text: 'Por favor seleccione un consultorio.'
                });
                return false;
            }
        }

        let duracion = $('#duraconsulta-wizard').val();

        // Guardar datos de horarios
        datosHorarios = {
            idconsultorio: consultorio,
            idclinica: clinica,
            duraconsulta: duracion
        };
        horariosConfigurados = true;
        return true;
    }

    // Cargar datos para horarios
    function loadHorariosData() {
        const typeConfig = window.typeConfiguration;
        
        // Cargar consultorios
        $.ajax({
            url: '/admin/consultorios/list',
            method: 'GET',
            success: function(response) {
                let options = '<option value="">Seleccione una opción</option>';
                response.forEach(function(consultorio) {
                    options += `<option value="${consultorio.idconsultorios}">${consultorio.vnumconsultorio}</option>`;
                });
                $('#offices-wizard').html(options);
            }
        });

        // Cargar clínicas solo si es type_configuration = 1
        if (typeConfig === 1) {
            $.ajax({
                url: '/admin/clinicas/list',
                method: 'GET',
                success: function(response) {
                    let options = '<option value="">Seleccione una opción</option>';
                    response.forEach(function(clinica) {
                        options += `<option value="${clinica.idclinica}">${clinica.tnombre}</option>`;
                    });
                    $('#clinica-wizard').html(options);
                }
            });
        }
    }

    // Función para cambiar consultorio en horarios
    window.changeOfficeWizard = function(userId) {
        $('#content-horario-consulta').html('');
        $('#content-duracion-consulta-wizard').hide();
        
        const typeConfig = window.typeConfiguration;
        let clinicaId = null;
        let consultorioId = null;
        
        if (typeConfig === 1) {
            clinicaId = $('#clinica-wizard').val() || null;
            consultorioId = $('#offices-wizard').val() || null;
        } else if (typeConfig === 2) {
            consultorioId = $('#offices-wizard').val() || null;
        }

        if (!consultorioId) return;

        const url = typeConfig === 1 
            ? `/admin/consultorio/${clinicaId}/${consultorioId}/${userId}/show`
            : `/admin/consultorio/null/${consultorioId}/${userId}/show`;

        axios.get(url)
            .then(function (response) {
                let result = response.data;
                $('#content-horario-consulta').html(result);
                $('#content-duracion-consulta-wizard').show();
                
                // Verificar validación después de cargar horarios
                setTimeout(function() {
                    checkHorariosValidation();
                }, 100);
            })
            .catch(e => { 
                console.error('Error cargando horarios:', e);
            });
    };

    // Función para verificar la validación de horarios
    function checkHorariosValidation() {
        const typeConfig = window.typeConfiguration;
        let isValid = false;
        
        if (typeConfig === 1) {
            const clinica = $('#clinica-wizard').val();
            const consultorio = $('#offices-wizard').val();
            isValid = !!clinica && !!consultorio;
        } else if (typeConfig === 2) {
            const consultorio = $('#offices-wizard').val();
            isValid = !!consultorio;
        }
        
        $('#finalizar-configuracion').prop('disabled', !isValid);
    }

    // Finalizar configuración
    function finalizarConfiguracion() {
        if (!validateHorariosForm()) return;
        
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea finalizar la configuración del panel?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                guardarConfiguracion();
            }
        });
    }

    // Guardar o actualizar clínica
    async function saveOrUpdateClinica() {
        if (clinicaId) {
            await $.ajax({
                url: `/admin/clinicas/${clinicaId}`,
                method: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: datosClinica
                }
            });
        } else {
            await $.ajax({
                url: '/admin/clinicas',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: datosClinica
                },
                success: function(response) {
                    clinicaId = response.idclinica;
                    // Agregar la nueva clínica al select y seleccionarla
                    const option = $('<option>', {
                        value: response.idclinica,
                        text: datosClinica.tnombre,
                        selected: true
                    });
                    $('#clinica-wizard').append(option);
                    $('#clinica-wizard').val(response.idclinica).trigger('change');
                }
            });
        }
    }

    // Guardar o actualizar consultorio
    async function saveOrUpdateConsultorio() {
        if (consultorioId) {
            await $.ajax({
                url: `/admin/consultorios/${consultorioId}`,
                method: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: datosConsultorio
                }
            });
        } else {
            await $.ajax({
                url: '/admin/consultorios',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: datosConsultorio
                },
                success: function(response) {
                    consultorioId = response.idconsultorios;
                    // Agregar el nuevo consultorio al select y seleccionarlo
                    const option = $('<option>', {
                        value: response.idconsultorios,
                        text: datosConsultorio.vnumconsultorio,
                        selected: true
                    });
                    $('#offices-wizard').append(option);
                    $('#offices-wizard').val(response.idconsultorios).trigger('change');
                }
            });
        }
    }

    function guardarConfiguracion() {
        // Mostrar loading
        Swal.fire({
            title: 'Guardando configuración...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar configuración final al backend
        axios.post('/admin/usuarios/config/finish/store', {
            userId: window.userId
        }).then(function(response) {
            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: '¡Configuración completada!',
                text: 'Su panel ha sido configurado exitosamente. El sistema está listo para usar.',
                confirmButtonText: 'Continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        }).catch(function(error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar la configuración. Por favor, intente nuevamente.'
            });
        });
    }

    // Habilitar botones de continuar cuando se completen los formularios
    $('#frm-clinica-wizard input, #frm-clinica-wizard select').on('change keyup', function() {
        const nombre = $('#inputNombreClinica').val();
        const folio = $('#inputFolioClinica').val();
        const estatus = $('#inputEstatusClinica').val();
        
        $('#next-to-step2').prop('disabled', !(nombre && folio && estatus));
    });

    $('#frm-consultorio-wizard input, #frm-consultorio-wizard textarea').on('change keyup', function() {
        const nombre = $('#inputNombreConsultorio').val();
        $('#next-to-step2, #next-to-step3').prop('disabled', !nombre);
    });

    $('#offices-wizard, #clinica-wizard').on('change', function() {
        checkHorariosValidation();
    });

    $('#duraconsulta-wizard').on('change', function() {
        checkHorariosValidation();
    });

    $(document).on('change', 'input[name="horarios[]"]', function() {
        checkHorariosValidation();
    });

    // Evento adicional para cuando se cargan horarios dinámicamente
    $(document).on('DOMNodeInserted', '#content-horario-consulta', function() {
        setTimeout(function() {
            checkHorariosValidation();
        }, 200);
    });

    // Mostrar solo los selects necesarios en horarios
    function updateHorariosSelects() {
        const typeConfig = window.typeConfiguration;
        
        if (typeConfig === 1) {
            $('#clinica-wizard').closest('.col-md-6').show();
            $('#offices-wizard').closest('.col-md-6').show();
        } else if (typeConfig === 2) {
            $('#clinica-wizard').closest('.col-md-6').hide();
            $('#offices-wizard').closest('.col-md-6').show();
        }
    }

    // Inicializar el wizard
    initializeWizard();
});

import Swal from 'sweetalert2';
import axios from 'axios';

// Configurar CSRF para todas las peticiones AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    let entornoSeleccionado = '';
    let clinicaId = null;
    let consultorioId = null;
    let horariosConfigurados = false;

    // Variables para almacenar datos de los formularios
    let datosClinica = {};
    let datosConsultorio = {};
    let datosHorarios = {};

    // Flujo dinámico de pasos
    let wizardSteps = [];
    let wizardIndex = 0;
    const stepMap = {
        'clinica': 2,
        'consultorio': 3,
        'horarios': 4
    };
    const stepLabels = {
        'clinica': 'Clínica',
        'consultorio': 'Consultorio',
        'horarios': 'Horarios'
    };

    function setWizardSteps() {
        if (entornoSeleccionado === 'clinica-only') {
            wizardSteps = ['clinica', 'horarios'];
        } else if (entornoSeleccionado === 'consultorio-only') {
            wizardSteps = ['consultorio', 'horarios'];
        } else if (entornoSeleccionado === 'ambos') {
            wizardSteps = ['clinica', 'consultorio', 'horarios'];
        } else {
            wizardSteps = [];
        }
        wizardIndex = 0;
        renderStepIndicators();
    }

    // Renderizar los indicadores de pasos dinámicamente
    function renderStepIndicators() {
        // Siempre mostrar el paso 1 (selección de entorno)
        let html = `<div class="step" id="step1-indicator">
            <div class="step-number">1</div>
            <div class="step-title">Selección de Entorno</div>
            <div class="step-connector"></div>
        </div>`;
        wizardSteps.forEach((step, idx) => {
            const num = idx + 2;
            html += `<div class="step" id="step${stepMap[step]}-indicator">
                <div class="step-number">${num}</div>
                <div class="step-title">${stepLabels[step]}</div>
                <div class="step-connector"></div>
            </div>`;
        });
        $('.steps').html(html);
    }

    // Paso 1: Selección de entorno
    $('.custom-radio').click(function() {
        $('.custom-radio').removeClass('selected');
        $(this).addClass('selected');
        
        entornoSeleccionado = $(this).attr('id');
        $('#entorno-seleccionado').val(entornoSeleccionado);
        
        // Habilitar botón continuar
        $('#next-to-step2').prop('disabled', false);
    });

    // Navegación entre pasos
    $('#next-to-step2').click(async function() {
        if (entornoSeleccionado) {
            // Enviar configuración de tipo al backend
            let tipoConfiguracion;
            if (entornoSeleccionado === 'clinica-only') {
                tipoConfiguracion = 1;
            } else if (entornoSeleccionado === 'consultorio-only') {
                tipoConfiguracion = 2;
            } else if (entornoSeleccionado === 'ambos') {
                tipoConfiguracion = 3;
            }

            try {
                await axios.post('/admin/usuarios/typeConfiguration/store', {
                    tipo: tipoConfiguracion,
                    user_id: window.userId
                });
                
                setWizardSteps();
                wizardIndex = 0;
                showWizardStep();
            } catch (error) {
                console.error('Error guardando tipo de configuración:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al guardar la configuración. Por favor, intente nuevamente.'
                });
            }
        }
    });

    // Avanzar paso (con creación/actualización de datos si aplica)
    $('#next-to-step3, #next-to-step4').click(async function() {
        const current = wizardSteps[wizardIndex];
        let valid = true;
        if (current === 'clinica') valid = validateClinicaForm();
        if (current === 'consultorio') valid = validateConsultorioForm();
        if (current === 'horarios') valid = validateHorariosForm();
        if (!valid) return;

        // Guardar/actualizar en backend si es paso de creación
        if (current === 'clinica') {
            await saveOrUpdateClinica();
        }
        if (current === 'consultorio') {
            await saveOrUpdateConsultorio();
        }

        wizardIndex++;
        showWizardStep();
    });

    // Botones de regreso
    $('#back-to-step1, #back-to-step2, #back-to-step3, #back-to-step4').click(function() {
        if (wizardIndex > 0) {
            wizardIndex--;
            showWizardStep();
        } else {
            // Regresar a selección de entorno
            $('.step-pane').removeClass('active');
            $('#step1').addClass('active');
            renderStepIndicators();
            updateStepIndicators(1);
        }
    });

    // Finalizar configuración (ahora en el paso de horarios)
    $('#finalizar-configuracion').click(function() {
        finalizarConfiguracion();
    });

    // Mostrar el paso correcto según el flujo
    function showWizardStep() {
        $('.step-pane').removeClass('active');
        // Paso actual
        const stepKey = wizardSteps[wizardIndex];
        const stepNum = stepMap[stepKey];
        $('#step' + stepNum).addClass('active');
        updateStepIndicators(stepNum);
        if (stepKey === 'horarios') {
            loadHorariosData();
        }
    }

    function updateStepIndicators(activeStep) {
        $('.step').removeClass('active completed');
        // Solo los steps renderizados
        $('.step').each(function(idx) {
            const stepNum = parseInt($(this).find('.step-number').text());
            if (stepNum < (wizardIndex + 2)) {
                $(this).addClass('completed');
            } else if (stepNum === (wizardIndex + 2)) {
                $(this).addClass('active');
            }
        });
        // El primer paso (selección de entorno)
        if (activeStep === 1) {
            $('#step1-indicator').addClass('active');
        }
    }

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
        let clinica = $('#clinica-wizard').length ? ($('#clinica-wizard').val() || null) : null;
        let consultorio = $('#offices-wizard').length ? ($('#offices-wizard').val() || null) : null;
        let duracion = $('#duraconsulta-wizard').val();

        if (entornoSeleccionado === 'clinica-only' && !clinica) {
            Swal.fire({
                icon: 'error',
                title: 'Campo requerido',
                text: 'Por favor seleccione una clínica.'
            });
            return false;
        }
        if (entornoSeleccionado === 'consultorio-only' && !consultorio) {
            Swal.fire({
                icon: 'error',
                title: 'Campo requerido',
                text: 'Por favor seleccione un consultorio.'
            });
            return false;
        }
        if (entornoSeleccionado === 'ambos' && (!clinica || !consultorio)) {
            Swal.fire({
                icon: 'error',
                title: 'Campos requeridos',
                text: 'Por favor seleccione una clínica y un consultorio.'
            });
            return false;
        }

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

        // Cargar clínicas
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

    // Función para cambiar consultorio en horarios
    window.changeOfficeWizard = function(userId) {
        $('#content-horario-consulta').html('');
        $('#content-duracion-consulta-wizard').hide();
        let clinicaId = $('#clinica-wizard').length ? ($('#clinica-wizard').val() || null) : null;
        let consultorioId = $('#offices-wizard').length ? ($('#offices-wizard').val() || null) : null;

        axios
        .get("/admin/consultorio/" + clinicaId+'/'+consultorioId+'/'+userId+'/show')
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
        let clinica = $('#clinica-wizard').length ? ($('#clinica-wizard').val() || null) : null;
        let consultorio = $('#offices-wizard').length ? ($('#offices-wizard').val() || null) : null;
        let isValid = false;
        if (entornoSeleccionado === 'clinica-only') {
            isValid = !!clinica;
        } else if (entornoSeleccionado === 'consultorio-only') {
            isValid = !!consultorio;
        } else if (entornoSeleccionado === 'ambos') {
            isValid = !!clinica && !!consultorio;
        }
        $('#finalizar-configuracion').prop('disabled', !isValid);
    }

    // Finalizar configuración
    function finalizarConfiguracion() {
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
        
        $('#next-to-step3').prop('disabled', !(nombre && folio && estatus));
    });

    $('#frm-consultorio-wizard input, #frm-consultorio-wizard textarea').on('change keyup', function() {
        const nombre = $('#inputNombreConsultorio').val();
        $('#next-to-step4').prop('disabled', !nombre);
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
        if (entornoSeleccionado === 'clinica-only') {
            $('#clinica-wizard').closest('.col-md-6').show();
            $('#offices-wizard').closest('.col-md-6').hide();
        } else if (entornoSeleccionado === 'consultorio-only') {
            $('#clinica-wizard').closest('.col-md-6').hide();
            $('#offices-wizard').closest('.col-md-6').show();
        } else {
            $('#clinica-wizard').closest('.col-md-6').show();
            $('#offices-wizard').closest('.col-md-6').show();
        }
    }

    // Llamar a estas funciones en el paso correspondiente
    function showWizardStep() {
        $('.step-pane').removeClass('active');
        const stepKey = wizardSteps[wizardIndex];
        const stepNum = stepMap[stepKey];
        $('#step' + stepNum).addClass('active');
        updateStepIndicators(stepNum);
        if (stepKey === 'horarios') {
            updateHorariosSelects();
            loadHorariosData();
        }
    }
});

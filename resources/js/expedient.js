function initExpedientDownloadButton() {
    let checkboxes = document.querySelectorAll('.selectExpedient');
    let btnDownload = document.getElementById('btn-download-expedient');
    let selectAll = document.getElementById('selectAll');

    function toggleDownloadButton() {
        let isAnySelected = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (btnDownload) {
            btnDownload.style.display = isAnySelected ? 'inline-block' : 'none';
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
            });
            toggleDownloadButton();
        });
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            toggleDownloadButton();
        });
    });

    // Inicializa el estado del botón al cargar
    toggleDownloadButton();
}

document.addEventListener('DOMContentLoaded', function() {
    initExpedientDownloadButton();
    initCollapseIcons();
    const btn = document.getElementById('btn-download-expedient');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let selectedExpedients = Array.from(document.querySelectorAll('.selectExpedient:checked'))
                .map(checkbox => checkbox.value);

            if (selectedExpedients.length === 0) {
                alert('Selecciona al menos un expediente.');
                return;
            }

            axios.post('/admin/expedientes/descargar-archivos', {
                expedients: selectedExpedients
            }, {
                responseType: 'blob'
            })
            .then(function (response) {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                // Intenta obtener el nombre del archivo del header
                let disposition = response.headers['content-disposition'];
                let fileName = 'expedientes.zip';
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    fileName = disposition.split('filename=')[1].replace(/['\"]/g, '');
                }
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            })
            .catch(function (error) {
                alert('No se pudo descargar el archivo.');
            });
        });
    }
});

if (window.Livewire) {
    window.Livewire.hook('message.processed', function() {
        initExpedientDownloadButton();
        initCollapseIcons();
    });
}

function initCollapseIcons() {
    document.querySelectorAll('.toggle-collapse').forEach(function(btn) {
        var targetSelector = btn.getAttribute('data-bs-target');
        var target = document.querySelector(targetSelector);
        var icon = btn.querySelector('i');
        if (!target) return;

        // Elimina listeners previos para evitar duplicados
        target.removeEventListener('show.bs.collapse', target._showListener || (()=>{}));
        target.removeEventListener('hide.bs.collapse', target._hideListener || (()=>{}));

        // Define listeners
        target._showListener = function () {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        };
        target._hideListener = function () {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        };
        target.addEventListener('show.bs.collapse', target._showListener);
        target.addEventListener('hide.bs.collapse', target._hideListener);

        // Forzar el toggle manualmente para evitar problemas de Livewire
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var bsCollapse = bootstrap.Collapse.getOrCreateInstance(target);
            if (target.classList.contains('show')) {
                bsCollapse.hide();
            } else {
                bsCollapse.show();
            }
        });
    });
}
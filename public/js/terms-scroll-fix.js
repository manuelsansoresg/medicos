// Script alternativo para manejar el scroll de términos y condiciones
console.log('Script alternativo de términos y condiciones cargado');

function initializeTermsScroll() {
    const iframe = document.getElementById('terms-pdf');
    const checkbox = document.getElementById('accept-terms');
    const nextButton = document.getElementById('next-to-step6');
    
    if (!iframe || !checkbox || !nextButton) {
        console.error('Elementos no encontrados');
        return;
    }
    
    let isEnabled = false;
    let scrollInterval;
    
    function enableCheckbox() {
        if (isEnabled) return;
        
        isEnabled = true;
        checkbox.disabled = false;
        checkbox.parentElement.classList.add('checkbox-enabled');
        
        if (scrollInterval) {
            clearInterval(scrollInterval);
        }
        
        console.log('✅ Checkbox habilitado');
        
        // Mostrar notificación visual
        const notification = document.createElement('div');
        notification.className = 'alert alert-success mt-3';
        notification.innerHTML = '<i class="fas fa-check-circle"></i> Puede ahora aceptar los términos y condiciones';
        checkbox.parentElement.appendChild(notification);
        
        // Remover notificación después de 3 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }
    
    function checkScroll() {
        try {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const scrollTop = iframeDoc.documentElement.scrollTop || iframeDoc.body.scrollTop;
            const scrollHeight = iframeDoc.documentElement.scrollHeight || iframeDoc.body.scrollHeight;
            const clientHeight = iframeDoc.documentElement.clientHeight || iframeDoc.body.clientHeight;
            
            console.log('Scroll check:', {
                scrollTop,
                scrollHeight,
                clientHeight,
                isAtBottom: scrollTop + clientHeight >= scrollHeight - 50
            });
            
            if (scrollTop + clientHeight >= scrollHeight - 50) {
                enableCheckbox();
            }
        } catch (error) {
            console.log('Error en scroll check:', error.message);
        }
    }
    
    // Inicializar cuando el iframe cargue
    iframe.onload = function() {
        console.log('Iframe cargado, iniciando verificación de scroll');
        
        // Verificar scroll cada 300ms
        scrollInterval = setInterval(checkScroll, 300);
        
        // Verificación inicial
        setTimeout(checkScroll, 1000);
        
        // Fallback después de 10 segundos
        setTimeout(() => {
            if (!isEnabled) {
                console.log('Fallback: habilitando checkbox');
                enableCheckbox();
            }
        }, 10000);
    };
    
    // Manejar cambio del checkbox
    checkbox.addEventListener('change', function() {
        window.termsAccepted = this.checked;
        nextButton.disabled = !this.checked;
        console.log('Checkbox cambiado:', this.checked);
    });
    
    // Si el iframe ya está cargado, inicializar inmediatamente
    if (iframe.contentDocument) {
        iframe.onload();
    }
}

// Función global para inicializar
window.initializeTermsScroll = initializeTermsScroll;

// Auto-inicializar si estamos en el paso 5
if (document.getElementById('step5') && document.getElementById('step5').classList.contains('active')) {
    setTimeout(initializeTermsScroll, 1000);
} 
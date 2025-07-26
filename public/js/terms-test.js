// Script de prueba para verificar el funcionamiento del scroll de términos y condiciones
console.log('Script de prueba de términos y condiciones cargado');

function testTermsScroll() {
    const iframe = document.getElementById('terms-pdf');
    const checkbox = document.getElementById('accept-terms');
    
    if (!iframe) {
        console.error('No se encontró el iframe de términos y condiciones');
        return;
    }
    
    if (!checkbox) {
        console.error('No se encontró el checkbox de términos y condiciones');
        return;
    }
    
    console.log('Elementos encontrados:', {
        iframe: iframe,
        checkbox: checkbox,
        checkboxDisabled: checkbox.disabled
    });
    
    // Función de prueba para verificar scroll
    function testScrollCheck() {
        try {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const scrollTop = iframeDoc.documentElement.scrollTop || iframeDoc.body.scrollTop;
            const scrollHeight = iframeDoc.documentElement.scrollHeight || iframeDoc.body.scrollHeight;
            const clientHeight = iframeDoc.documentElement.clientHeight || iframeDoc.body.clientHeight;
            
            console.log('Estado del scroll:', {
                scrollTop: scrollTop,
                scrollHeight: scrollHeight,
                clientHeight: clientHeight,
                isAtBottom: scrollTop + clientHeight >= scrollHeight - 50
            });
            
            if (scrollTop + clientHeight >= scrollHeight - 50) {
                console.log('¡Usuario llegó al final del documento!');
                checkbox.disabled = false;
                console.log('Checkbox habilitado:', checkbox.disabled);
            }
        } catch (error) {
            console.error('Error al verificar scroll:', error);
        }
    }
    
    // Ejecutar verificación cada segundo
    const testInterval = setInterval(testScrollCheck, 1000);
    
    // Detener después de 30 segundos
    setTimeout(() => {
        clearInterval(testInterval);
        console.log('Prueba de scroll terminada');
    }, 30000);
}

// Función para ejecutar la prueba
window.testTermsScroll = testTermsScroll;

console.log('Para ejecutar la prueba, escriba: testTermsScroll()'); 
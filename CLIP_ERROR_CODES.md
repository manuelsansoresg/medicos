# Códigos de Error de Clip - Documentación

## Códigos de Error Conocidos

### Errores de Validación de Tarjeta
- **CL2200**: Datos de la tarjeta inválidos
- **CL2290**: Tarjeta rechazada por el banco
- **CL1001**: Tarjeta expirada
- **CL1002**: Fondos insuficientes
- **CL1003**: Tarjeta bloqueada
- **CL1004**: Límite de transacciones excedido

### Errores de Configuración
- **CL1127**: Tarjeta no habilitada para este tipo de pago (Merchant without amex affiliations)
- **AI1300**: Problema de autenticación

## Cómo Obtener los Códigos Reales

### 1. Documentación Oficial
- Visitar: https://docs.clip.mx/api/errors
- Revisar la documentación oficial de Clip para códigos actualizados

### 2. Logs de Desarrollo
- Revisar los logs de la aplicación cuando ocurran errores
- Los códigos reales aparecerán en la respuesta de la API

### 3. Testing
- Probar con tarjetas de prueba que generen diferentes errores
- Documentar los códigos que aparezcan

## Estructura de Respuesta de Error

```json
{
    "response": {
        "error_code": "CL1127",
        "message": "Bad Request",
        "detail": [
            "merchant without amex affiliations"
        ]
    },
    "status": null
}
```

## Implementación en JavaScript

```javascript
switch (clipError.error_code) {
    case 'CL1127':
        errorMessage = 'Error: Su tarjeta no está habilitada para este tipo de pago.';
        break;
    case 'CL2200':
        errorMessage = 'Error: Datos de la tarjeta inválidos.';
        break;
    // ... más casos
    default:
        errorMessage = 'Error en el procesamiento del pago.';
        errorDetails = `Código de error: ${clipError.error_code}`;
}
```

## Notas Importantes

1. **Los códigos pueden cambiar**: Clip puede actualizar sus códigos de error
2. **Siempre usar el mensaje del servidor**: `clipError.message` contiene el mensaje oficial
3. **Logging**: Siempre registrar los errores para debugging
4. **Fallback**: Siempre tener un caso `default` para códigos desconocidos

## Actualización de Códigos

Para mantener esta documentación actualizada:

1. Revisar regularmente la documentación oficial de Clip
2. Monitorear los logs de errores en producción
3. Actualizar el JavaScript cuando se encuentren nuevos códigos
4. Probar con diferentes escenarios de error 
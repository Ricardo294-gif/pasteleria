/**
 * Script de soporte para garantizar que las alertas personalizadas funcionen correctamente.
 * Este archivo se debe cargar después del custom-alerts.js original.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Comprobar si existe el sistema de alertas personalizadas
    if (!window.customAlerts) {
        console.warn('Sistema de alertas no detectado. Creando versión simplificada...');
        
        // Crear contenedor de alertas si no existe
        let alertsContainer = document.querySelector('.alerts-container');
        if (!alertsContainer) {
            alertsContainer = document.createElement('div');
            alertsContainer.className = 'alerts-container';
            document.body.appendChild(alertsContainer);
            
            // Añadir estilos básicos si no están
            if (!document.querySelector('style#custom-alerts-emergency')) {
                const style = document.createElement('style');
                style.id = 'custom-alerts-emergency';
                style.textContent = `
                    .alerts-container {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        width: 350px;
                        max-width: calc(100% - 40px);
                        z-index: 9999;
                    }
                    .custom-alert {
                        position: relative;
                        padding: 1rem 1.25rem;
                        margin-bottom: 1rem;
                        border: none;
                        border-radius: 15px;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                        display: flex;
                        align-items: flex-start;
                        background-color: #fff;
                        animation: alertSlideIn 0.4s ease forwards;
                    }
                    .custom-alert::before {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        height: 100%;
                        width: 5px;
                        border-radius: 3px 0 0 3px;
                    }
                    .custom-alert .icon {
                        font-size: 1.25rem;
                        margin-right: 0.75rem;
                    }
                    .custom-alert .content {
                        flex: 1;
                    }
                    .custom-alert .btn-close {
                        background: transparent;
                        border: none;
                        cursor: pointer;
                        font-size: 1.2rem;
                        margin-left: 10px;
                    }
                    .custom-alert-success {
                        background-color: #f0fffa;
                        color: #0d6853;
                    }
                    .custom-alert-success::before {
                        background-color: #20c997;
                    }
                    .custom-alert-success .icon {
                        color: #20c997;
                    }
                    @keyframes alertSlideIn {
                        from {
                            opacity: 0;
                            transform: translateX(50px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        // Implementación mínima de alertas
        window.customAlerts = {
            show: function(type, message, autoDismiss = true) {
                console.log('Mostrando alerta de emergencia:', type, message);
                
                // Crear el elemento de alerta
                const alert = document.createElement('div');
                alert.className = `custom-alert custom-alert-${type}`;
                
                // Determinar icono según el tipo
                let iconClass = '';
                switch(type) {
                    case 'success': iconClass = 'bi-check-circle-fill'; break;
                    case 'danger': iconClass = 'bi-exclamation-triangle-fill'; break;
                    case 'warning': iconClass = 'bi-exclamation-circle-fill'; break;
                    case 'info': iconClass = 'bi-info-circle-fill'; break;
                }
                
                // Construir HTML interno
                alert.innerHTML = `
                    <div class="icon">
                        <i class="bi ${iconClass}"></i>
                    </div>
                    <div class="content">
                        ${message}
                    </div>
                    <button type="button" class="btn-close" aria-label="Cerrar">×</button>
                `;
                
                // Agregar al DOM
                alertsContainer.appendChild(alert);
                
                // Configurar botón de cierre
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        alertsContainer.removeChild(alert);
                    });
                }
                
                // Auto-cerrar después de 5 segundos
                if (autoDismiss) {
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alertsContainer.removeChild(alert);
                        }
                    }, 5000);
                }
                
                return alert;
            }
        };
    }
    
    // Procesar mensajes de sesión pendientes
    const pendingMessages = {
        success: document.querySelector('meta[name="session-success"]')?.getAttribute('content'),
        error: document.querySelector('meta[name="session-error"]')?.getAttribute('content'),
        warning: document.querySelector('meta[name="session-warning"]')?.getAttribute('content'),
        info: document.querySelector('meta[name="session-info"]')?.getAttribute('content')
    };
    
    // Mostrar mensajes si hay alguno pendiente
    if (pendingMessages.success) {
        window.customAlerts.show('success', pendingMessages.success);
    }
    if (pendingMessages.error) {
        window.customAlerts.show('danger', pendingMessages.error);
    }
    if (pendingMessages.warning) {
        window.customAlerts.show('warning', pendingMessages.warning);
    }
    if (pendingMessages.info) {
        window.customAlerts.show('info', pendingMessages.info);
    }
});

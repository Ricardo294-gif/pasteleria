// Script para manejar las alertas personalizadas
document.addEventListener('DOMContentLoaded', function() {
    // Crear el contenedor de alertas si no existe
    let alertsContainer = document.querySelector('.alerts-container');
    if (!alertsContainer) {
        alertsContainer = document.createElement('div');
        alertsContainer.className = 'alerts-container';
        document.body.appendChild(alertsContainer);
    }
    
    // Crear contenedor de alertas específico para la página de perfil
    if (window.location.pathname.includes('/perfil')) {
        let profileAlertsContainer = document.querySelector('.profile-alerts-container');
        if (!profileAlertsContainer) {
            profileAlertsContainer = document.createElement('div');
            profileAlertsContainer.className = 'profile-alerts-container';
            
            // Insertar al principio de la página de contenido
            const contentArea = document.querySelector('.content-area');
            if (contentArea) {
                contentArea.insertBefore(profileAlertsContainer, contentArea.firstChild);
            }
        }
    }
    
    // Mover todas las alertas existentes al contenedor apropiado
    moveExistingAlerts();
    
    // Inicializar todas las alertas
    initializeAlerts();
    
    // Función para mover las alertas existentes al contenedor apropiado
    function moveExistingAlerts() {
        const existingAlerts = document.querySelectorAll('.custom-alert');
        
        // Determinar si estamos en la página de perfil
        const isProfilePage = window.location.pathname.includes('/perfil');
        const profileAlertsContainer = document.querySelector('.profile-alerts-container');
        
        existingAlerts.forEach(alert => {
            // Si estamos en la página de perfil y existe el contenedor de perfil
            if (isProfilePage && profileAlertsContainer) {
                // Agregar clase de perfil y mover al contenedor de perfil
                alert.classList.add('profile-alert');
                
                // Desconectar el alert de su padre actual
                if (alert.parentElement) {
                    alert.parentElement.removeChild(alert);
                }
                
                // Añadirlo al contenedor de perfil
                profileAlertsContainer.appendChild(alert);
            } else {
                // Solo mover al contenedor global si no está ya en él
                if (alert.parentElement !== alertsContainer) {
                    // Desconectar el alert de su padre actual
                    if (alert.parentElement) {
                        alert.parentElement.removeChild(alert);
                    }
                    // Añadirlo al contenedor global
                    alertsContainer.appendChild(alert);
                }
            }
        });
    }
    
    // Función para inicializar alertas
    function initializeAlerts() {
        const alerts = document.querySelectorAll('.custom-alert');
        
        // Usar un Set para rastrear mensajes únicos y evitar duplicados
        const uniqueMessages = new Set();
        
        alerts.forEach(alert => {
            const messageContent = alert.querySelector('.content')?.textContent?.trim() || '';
            const messageType = Array.from(alert.classList)
                .find(cls => cls.startsWith('custom-alert-'))
                ?.replace('custom-alert-', '') || '';
            
            const messageKey = `${messageType}:${messageContent}`;
            
            // Si es un duplicado, eliminar esta alerta
            if (uniqueMessages.has(messageKey)) {
                alert.remove();
                return;
            }
            
            // Registrar este mensaje como visto
            uniqueMessages.add(messageKey);
            
            // Configurar botón de cierre si existe
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeAlert(alert);
                });
            }
            
            // Si la alerta debe cerrarse automáticamente
            if (alert.classList.contains('auto-dismiss')) {
                setTimeout(() => {
                    closeAlert(alert);
                }, 5000); // 5 segundos
            }
        });
    }
    
    // Función para cerrar una alerta con animación
    function closeAlert(alert) {
        // Añadir la clase que inicia la animación de salida
        alert.classList.add('fade-out');
        
        // Determinar tiempo de la animación según el tipo de alerta
        const animationDuration = alert.classList.contains('profile-alert') ? 750 : 600;
        
        // Eliminar el elemento después de que la animación termine
        setTimeout(() => {
            // Verificar que el elemento todavía exista
            if (alert && alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, animationDuration);
    }
    
    // Exponer funciones globalmente para uso en otros scripts
    window.customAlerts = {
        initialize: initializeAlerts,
        close: closeAlert,
        
        // Función para deshabilitar eventos preventDefault en el documento
        disablePreventDefault: function() {
            // Guardar la versión original del método
            const originalPreventDefault = Event.prototype.preventDefault;
            
            // Reemplazar con una versión que no haga nada
            Event.prototype.preventDefault = function() {
                console.log('preventDefault fue desactivado temporalmente');
                return true;
            };
            
            // Restaurar después de un tiempo
            setTimeout(() => {
                Event.prototype.preventDefault = originalPreventDefault;
                console.log('preventDefault restaurado');
            }, 2000);
        },
        
        // Función para crear una nueva alerta dinámicamente
        create: function(type, message, autoDismiss = true, parent = null) {
            // Verificar si ya existe una alerta con el mismo mensaje y tipo
            const existingAlerts = document.querySelectorAll('.custom-alert');
            for (let alert of existingAlerts) {
                const existingContent = alert.querySelector('.content')?.textContent?.trim() || '';
                if (existingContent === message && alert.classList.contains(`custom-alert-${type}`)) {
                    // Ya existe una alerta igual, no crear duplicado
                    return alert;
                }
            }
            
            // Crear el elemento de alerta
            const alert = document.createElement('div');
            alert.className = `custom-alert custom-alert-${type}`;
            if (autoDismiss) {
                alert.classList.add('auto-dismiss');
            }
            
            // Determinar icono según el tipo
            let icon = '';
            switch(type) {
                case 'success':
                    icon = 'bi-check-circle-fill';
                    break;
                case 'danger':
                    icon = 'bi-exclamation-triangle-fill';
                    break;
                case 'warning':
                    icon = 'bi-exclamation-circle-fill';
                    break;
                case 'info':
                    icon = 'bi-info-circle-fill';
                    break;
            }
            
            // Construir HTML interno
            alert.innerHTML = `
                <div class="icon">
                    <i class="bi ${icon}"></i>
                </div>
                <div class="content">
                    ${message}
                </div>
                <button type="button" class="btn-close" aria-label="Cerrar"></button>
            `;
            
            // Determinar contenedor apropiado
            let targetContainer;
            const isProfilePage = window.location.pathname.includes('/perfil');
            
            if (isProfilePage) {
                // Buscar el contenedor de alertas de perfil
                let profileContainer = document.querySelector('.profile-alerts-container');
                if (!profileContainer) {
                    // Si no existe, crearlo e insertarlo
                    profileContainer = document.createElement('div');
                    profileContainer.className = 'profile-alerts-container';
                    
                    const contentArea = document.querySelector('.content-area');
                    if (contentArea) {
                        contentArea.insertBefore(profileContainer, contentArea.firstChild);
                    } else {
                        // Fallback al contenedor global
                        document.querySelector('.alerts-container').appendChild(alert);
                        targetContainer = document.querySelector('.alerts-container');
                    }
                }
                
                // Añadir clase específica para alertas de perfil
                alert.classList.add('profile-alert');
                targetContainer = profileContainer;
            } else {
                // Usar el contenedor global para páginas normales
                targetContainer = document.querySelector('.alerts-container');
            }
            
            // Agregar al DOM en el contenedor apropiado
            if (targetContainer) {
                targetContainer.appendChild(alert);
            } else {
                // Si por alguna razón no hay contenedor, usar el body como fallback
                document.body.appendChild(alert);
            }
            
            // Inicializar la nueva alerta
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeAlert(alert);
                });
            }
            
            // Si debe cerrarse automáticamente
            if (autoDismiss) {
                // Tiempo más largo para alertas de perfil
                const dismissTime = alert.classList.contains('profile-alert') ? 5000 : 4000;
                setTimeout(() => {
                    closeAlert(alert);
                }, dismissTime);
            }
            
            return alert;
        },
        
        // Función para mostrar una alerta
        show: function(type, message, autoDismiss = true) {
            return this.create(type, message, autoDismiss);
        }
    };
}); 
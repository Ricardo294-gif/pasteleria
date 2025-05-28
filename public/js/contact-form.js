document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.php-email-form');

    if (contactForm) {
        let isSubmitting = false;
        
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Evitar múltiples envíos
            if (isSubmitting) {
                return false;
            }
            
            // Ocultar mensaje de error si existe
            this.querySelector('.error-message').style.display = 'none';
            
            // Si hay un reCAPTCHA en el formulario (para usuarios no autenticados)
            const recaptchaElement = this.querySelector('.g-recaptcha');

            if (recaptchaElement) {
                const recaptchaResponse = grecaptcha.getResponse();

                if (!recaptchaResponse) {
                    const recaptchaError = this.querySelector('.recaptcha-error');
                    recaptchaError.style.display = 'block';

                    // Ocultar el mensaje de error después de 4 segundos
                    setTimeout(function() {
                        recaptchaError.style.display = 'none';
                    }, 4000);

                    return false;
                }
            }
            
            // Marcar como en proceso de envío
            isSubmitting = true;
            
            // Deshabilitar botón de envío permanentemente y añadir animación de cargando
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.classList.add('btn-no-hover');
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
            
            // Agregar estilos directamente para anular los efectos hover
            submitButton.style.pointerEvents = 'none';
            submitButton.style.opacity = '0.8';
            submitButton.style.cursor = 'default';
            
            // Enviar formulario mediante AJAX
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text || 'Ha ocurrido un error al enviar el mensaje');
                    });
                }
                return response.text();
            })
            .then(data => {
                this.reset();
                
                // Cambiar el texto del botón a enviado pero mantenerlo deshabilitado
                submitButton.innerHTML = 'Enviado ✓';
                submitButton.classList.add('btn-success');
                submitButton.classList.add('btn-no-hover');
                
                // Reset recaptcha si existe
                if (recaptchaElement && typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
                
                // Mostrar alerta flotante de éxito como las que ya están implementadas
                mostrarAlerta('success', '¡Mensaje enviado correctamente!', 'Te hemos enviado un correo de confirmación.');
                
                // Mantenemos isSubmitting en true para evitar nuevos envíos
            })
            .catch(error => {
                // En caso de error, dejamos el botón deshabilitado pero cambiamos el mensaje
                submitButton.innerHTML = 'Error al enviar';
                submitButton.classList.add('btn-danger');
                submitButton.classList.add('btn-no-hover');
                
                // No mostrar mensaje de error de tiempo de espera
                if (!error.message.includes('espera')) {
                this.querySelector('.error-message').textContent = error.message;
                this.querySelector('.error-message').style.display = 'block';
                }
                
                // Reset recaptcha si existe
                if (recaptchaElement && typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
            }
                
                // Mantenemos isSubmitting en true para evitar nuevos envíos
            });
        });
    }
    
    // Función para mostrar alertas como las que ya están implementadas en la página
    function mostrarAlerta(tipo, titulo, mensaje) {
        // Verificar si existe el contenedor de alertas o crearlo
        let alertsContainer = document.querySelector('.alerts-container');
        if (!alertsContainer) {
            alertsContainer = document.createElement('div');
            alertsContainer.className = 'alerts-container';
            document.body.appendChild(alertsContainer);
        }
        
        // Crear la alerta
        const alertElement = document.createElement('div');
        alertElement.className = `custom-alert custom-alert-${tipo} auto-dismiss`;
        
        // Construir contenido de la alerta
        alertElement.innerHTML = `
            <div class="icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="content">
                <strong>${titulo}</strong>
                <div>${mensaje}</div>
            </div>
            <button type="button" class="btn-close">
                <i class="bi bi-x"></i>
            </button>
        `;
        
        // Añadir la alerta al contenedor
        alertsContainer.appendChild(alertElement);
        
        // Configurar el botón de cierre
        const closeButton = alertElement.querySelector('.btn-close');
        closeButton.addEventListener('click', function() {
            alertElement.classList.add('fade-out');
            setTimeout(() => {
                if (alertsContainer.contains(alertElement)) {
                    alertsContainer.removeChild(alertElement);
                }
                
                // Eliminar contenedor si está vacío
                if (alertsContainer.children.length === 0) {
                    document.body.removeChild(alertsContainer);
                }
            }, 600);
        });
        
        // Auto cierre después de 5 segundos
        setTimeout(() => {
            if (alertsContainer.contains(alertElement)) {
                alertElement.classList.add('fade-out');
                setTimeout(() => {
                    if (alertsContainer.contains(alertElement)) {
                        alertsContainer.removeChild(alertElement);
                    }
                    
                    // Eliminar contenedor si está vacío
                    if (alertsContainer.children.length === 0 && document.body.contains(alertsContainer)) {
                        document.body.removeChild(alertsContainer);
                    }
                }, 600);
            }
        }, 5000);
    }
});

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Compra - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #ff7070;
            --bg-color: #fef6e6;
            --text-color: #555555;
            --border-color: #ff7070;
        }

        /* Variables para modo oscuro */
        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #e0e0e0;
            --border-color: #ff7070;
            --input-bg: #2d2d2d;
            --card-bg: #2d2d2d;
            --label-color: #c0c0c0;
            --title-color: #ffffff;
            --link-color: #ff9494;
            --alert-bg: rgba(255, 112, 112, 0.1);
            --toast-bg: #2d2d2d;
            --toast-shadow: rgba(0, 0, 0, 0.3);
            --toast-text: #e0e0e0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        .verification-container {
            max-width: 450px;
            width: 100%;
        }

        .alerta-info {
            margin-bottom: 15px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            margin-top: -20px;
        }
        
        .logo-text {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            color: #000;
            text-decoration: none;
            margin: 0;
            margin-left: 30px;
        }

        [data-theme="dark"] .logo-text {
            color: #ffffff;
        }
        
        .logo-text .dulce {
            color: #ff7070;
        }
        
        .logo-image {
            max-width: 50px;
            margin-left: 10px;
        }

        .verification-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            position: relative;
            transition: background-color 0.5s ease, box-shadow 0.5s ease;
        }

        [data-theme="dark"] .verification-card {
            background-color: var(--card-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .verification-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: #000;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            position: relative;
            margin-top: -10px;
        }

        [data-theme="dark"] .verification-title {
            color: var(--title-color);
        }
        
        .verification-title:after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 1px;
            background-color: #000;
        }

        [data-theme="dark"] .verification-title:after {
            background-color: #444;
        }

        .form-label {
            font-weight: normal;
            margin-bottom: 8px;
            color: #666;
        }

        [data-theme="dark"] .form-label {
            color: var(--label-color);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            background-color: white;
            text-align: center;
            letter-spacing: 3px;
            font-size: 1.2rem;
        }

        [data-theme="dark"] .form-control {
            background-color: var(--input-bg);
            color: var(--text-color);
            border-color: #444;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

        [data-theme="dark"] .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.15);
        }
        
        /* Eliminar el fondo azul celeste en autocompletar */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        [data-theme="dark"] input:-webkit-autofill,
        [data-theme="dark"] input:-webkit-autofill:hover, 
        [data-theme="dark"] input:-webkit-autofill:focus,
        [data-theme="dark"] input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #2d2d2d inset !important;
            -webkit-text-fill-color: var(--text-color) !important;
        }

        .btn-verify {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 10px;
            font-weight: 500;
            border-radius: 8px;
            width: 100%;
            margin: 10px 0;
            font-family: 'Dancing Script', cursive;
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }
        
        .btn-verify:hover {
            transform: translateY(-3px);
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-verify:focus, .btn-verify:active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

        .resend-container {
            text-align: center;
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .btn-link {
            color: var(--primary-color);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-decoration: underline;
            display: inline;
            font-size: inherit;
        }

        [data-theme="dark"] .btn-link {
            color: var(--link-color);
        }
        
        /* Prevenir cambio de color en hover */
        .btn-link:hover,
        .btn-link:focus,
        .btn-link:active {
            color: var(--primary-color);
            text-decoration: underline;
        }

        [data-theme="dark"] .btn-link:hover,
        [data-theme="dark"] .btn-link:focus,
        [data-theme="dark"] .btn-link:active {
            color: var(--link-color);
        }
        
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: transparent;
            border: none;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            cursor: pointer;
            transition: transform 0.2s ease;
            text-decoration: none;
        }
        
        .back-button:hover {
            transform: translateX(-3px);
            color: var(--primary-color);
        }
        
        .back-button i {
            margin-right: 5px;
        }
        
        /* Alerta flotante personalizada */
        .custom-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            max-width: 350px;
            display: flex;
            align-items: center;
            border-left: 4px solid var(--primary-color);
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .custom-toast {
            background: var(--toast-bg);
            box-shadow: 0 4px 15px var(--toast-shadow);
        }
        
        .custom-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .custom-toast-icon {
            margin-right: 12px;
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .custom-toast-message {
            color: #555;
            font-size: 0.9rem;
            flex: 1;
        }

        [data-theme="dark"] .custom-toast-message {
            color: var(--toast-text);
        }

        [data-theme="dark"] .alerta-info strong {
            color: #ffffff;
        }

        /* Botón de cambio de tema */
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            left: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            color: #ff7070;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border: none;
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .theme-toggle-btn {
            background-color: #333;
            color: #ff9494;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
        }

        .theme-toggle-btn i {
            font-size: 1.2rem;
        }

        [data-theme="dark"] .invalid-feedback {
            color: #ff8080;
        }
    </style>
</head>
<body>
    <a href="{{ route('carrito.index') }}" class="back-button">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    
    <!-- Botón para cambiar tema -->
    <button id="theme-toggle" class="theme-toggle-btn">
        <i class="bi bi-moon-fill" id="theme-icon"></i>
    </button>
    
    <div class="verification-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi sueño <span class="dulce">dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Mi Sueño Dulce" class="logo-image">
        </div>
        
        <div class="verification-card">
            <h2 class="verification-title">Verificación de Compra</h2>
            
            <!-- Mensajes de éxito y error -->
            @if (session('success'))
                <div id="customToastSuccess" class="custom-toast">
                    <div class="custom-toast-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="custom-toast-message">{{ session('success') }}</div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toast = document.getElementById('customToastSuccess');
                        if (toast) {
                            setTimeout(() => toast.classList.add('show'), 100);
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => toast.remove(), 300);
                            }, 5000);
                        }
                    });
                </script>
            @endif

            @if (session('error'))
                <div id="customToastError" class="custom-toast" style="border-color: #dc3545;">
                    <div class="custom-toast-icon" style="color: #dc3545;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div class="custom-toast-message">{{ session('error') }}</div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toast = document.getElementById('customToastError');
                        if (toast) {
                            setTimeout(() => toast.classList.add('show'), 100);
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => toast.remove(), 300);
                            }, 5000);
                        }
                    });
                </script>
            @endif
            
            <!-- Alerta informativa -->
            <div class="alerta-info" role="alert">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0" style="font-size: 0.9rem;">
                            Hemos enviado un código de verificación a <strong>{{ session('email') }}</strong>. 
                            Por favor, introdúcelo a continuación para continuar con tu compra.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('payment.verify') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="verification_code" class="form-label">Código de verificación</label>
                    <input 
                        type="text" 
                        name="verification_code" 
                        id="verification_code" 
                        class="form-control @error('verification_code') is-invalid @enderror" 
                        placeholder="000000"
                        maxlength="6" 
                        required 
                        autocomplete="off">
                    @error('verification_code')
                        <div class="invalid-feedback text-center">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" id="verifyCodeButton" class="btn-verify">
                    Verificar y Continuar
                </button>
            </form>
            
            <div class="resend-container">
                <p class="mb-0">
                    ¿No recibiste el código? 
                    <button type="button" id="resendCodeBtn" class="btn-link">
                        Reenviar código
                    </button>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar el envío del formulario de verificación
            const verificationForm = document.querySelector('form');
            const verifyCodeButton = document.getElementById('verifyCodeButton');
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // Comprobar el tema actual del sistema o el guardado previamente
            const currentTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            if (currentTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            }
            
            // Función para cambiar el tema
            themeToggleBtn.addEventListener('click', function() {
                if (document.body.getAttribute('data-theme') === 'dark') {
                    document.body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                } else {
                    document.body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                }
            });

            if (verificationForm && verifyCodeButton) {
                verificationForm.addEventListener('submit', function(e) {
                    // Verificar que el código esté completo
                    const codeInput = document.getElementById('verification_code');
                    if (!codeInput || !codeInput.value.trim()) {
                        return; // No deshabilitar el botón si falta el código
                    }

                    // Deshabilitar el botón para evitar múltiples envíos
                    verifyCodeButton.disabled = true;
                    verifyCodeButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Verificando...';
                });
            }

            // Mejorar entrada del código
            const codeInput = document.getElementById('verification_code');
            if (codeInput) {
                // Solo permitir números
                codeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
                });

                // Enfocar automáticamente
                codeInput.focus();
            }

            // Manejar el reenvío de código con AJAX
            const resendButton = document.getElementById('resendCodeBtn');
            let cooldownActive = false;
            let cooldownTimer = null;

            function startCooldown(seconds) {
                if (cooldownTimer) {
                    clearInterval(cooldownTimer);
                }

                cooldownActive = true;
                resendButton.disabled = true;

                let remainingTime = seconds;
                resendButton.innerHTML = `Reenviar código (${remainingTime}s)`;

                cooldownTimer = setInterval(() => {
                    remainingTime--;
                    resendButton.innerHTML = `Reenviar código (${remainingTime}s)`;
                    
                    if (remainingTime <= 0) {
                        clearInterval(cooldownTimer);
                        cooldownActive = false;
                        resendButton.disabled = false;
                        resendButton.textContent = 'Reenviar código';
                    }
                }, 1000);
            }

            // Iniciar la cuenta atrás automáticamente al cargar la página
            if (resendButton) {
                // Iniciar el temporizador con 30 segundos al cargar la página
                startCooldown(30);
            }

            // Función para mostrar alertas personalizadas
            function showCustomToast(message, type = 'success') {
                // Eliminar toasts anteriores
                const existingToasts = document.querySelectorAll('.custom-toast');
                existingToasts.forEach(toast => toast.remove());
                
                // Crear nuevo toast
                const toast = document.createElement('div');
                toast.className = 'custom-toast';
                toast.style.borderColor = type === 'success' ? '#ff7070' : '#dc3545';
                
                const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
                const iconColor = type === 'success' ? '#ff7070' : '#dc3545';
                
                toast.innerHTML = `
                    <div class="custom-toast-icon" style="color: ${iconColor}">
                        <i class="bi ${iconClass}"></i>
                    </div>
                    <div class="custom-toast-message">${message}</div>
                `;
                
                document.body.appendChild(toast);
                
                // Mostrar con animación
                setTimeout(() => toast.classList.add('show'), 10);
                
                // Ocultar después de 5 segundos
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            resendButton.addEventListener('click', function() {
                // Si hay un período de enfriamiento activo, ignorar el clic
                if (cooldownActive) {
                    return;
                }

                // Cambiar el texto del botón durante la petición
                const button = this;
                const originalText = button.textContent;
                button.textContent = 'Enviando...';
                button.disabled = true;

                // Crear el objeto con los datos a enviar
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Realizar la petición AJAX
                fetch('{{ route("payment.resend") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Respuesta del servidor:', response.status, response.statusText);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de respuesta:', data);
                    // Mostrar mensaje personalizado
                    if (data.success) {
                        showCustomToast(data.message, 'success');
                    } else {
                        // Evitar mostrar el mensaje de espera de 30 segundos
                        if (!data.message.includes('espera') && !data.message.includes('segundos')) {
                            showCustomToast(data.message || 'Error al reenviar el código', 'error');
                        }
                    }
                    
                    // Iniciar período de enfriamiento de 30 segundos
                    startCooldown(30);
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.textContent = originalText;
                    button.disabled = false;
                    showCustomToast('Error al reenviar el código. Inténtalo de nuevo.', 'error');
                });
            });
        });
    </script>
</body>
</html>

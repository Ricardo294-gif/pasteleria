<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            --spam-bg: #333;
            --highlight-bg: #3d1f1f;
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
        }

        .verification-container {
            max-width: 450px;
            width: 100%;
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

        /* Barra de progreso */
        .auth-progress-container {
            max-width: 450px;
            margin: 0 auto 25px;
            padding: 0 15px;
        }
        
        .auth-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 10px;
        }
        
        .auth-progress-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        [data-theme="dark"] .auth-progress-line {
            background-color: #444;
        }
        
        .auth-progress-fill {
            position: absolute;
            top: 15px;
            left: 0;
            width: 66.66%;
            height: 3px;
            background-color: var(--primary-color);
            z-index: 1;
        }
        
        .auth-step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #fff;
            border: 3px solid #e0e0e0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            color: #777;
            position: relative;
        }

        [data-theme="dark"] .auth-step {
            background-color: #333;
            border-color: #444;
            color: #999;
        }
        
        .auth-step.active {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
            color: white;
        }
        
        .auth-step.completed {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
            color: white;
        }
        
        .auth-step-text {
            position: absolute;
            top: 35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #777;
            width: 80px;
            text-align: center;
        }

        [data-theme="dark"] .auth-step-text {
            color: #999;
        }
        
        .auth-step.active .auth-step-text,
        .auth-step.completed .auth-step-text {
            color: var(--primary-color);
            font-weight: 500;
        }

        .verification-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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

        .alert {
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            background-color: rgba(255, 112, 112, 0.05);
            padding: 15px;
            margin-bottom: 20px;
        }

        [data-theme="dark"] .alert {
            background-color: var(--alert-bg);
            color: var(--text-color);
        }

        [data-theme="dark"] .alert-info {
            border-color: var(--primary-color);
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
        
        .countdown-timer {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-left: 4px;
        }
        
        .invalid-feedback {
            display: block;
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        [data-theme="dark"] .invalid-feedback {
            color: #ff8080;
        }
        
        /* Eliminar íconos de error pero mantener el borde rojo */
        .form-control.is-invalid {
            background-image: none;
        }
        
        /* Estilo para los mensajes de error */
        .text-danger {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        [data-theme="dark"] .text-danger {
            color: #ff8080;
        }
        
        /* Estilo para la alerta de error */
        .alert-danger {
            border-left: 4px solid #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            color: #721c24;
            font-weight: 500;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        [data-theme="dark"] .alert-danger {
            background-color: rgba(220, 53, 69, 0.15);
            color: #ff8080;
            border-color: #dc3545;
        }
        
        .alert-danger::before {
            content: "\f071";
            font-family: "bootstrap-icons";
            margin-right: 10px;
            color: #dc3545;
            font-size: 1.1rem;
        }

        [data-theme="dark"] .alert-danger::before {
            color: #ff8080;
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
        }
        
        .back-button i {
            margin-right: 5px;
        }
        
        /* Mensaje de verificación */
        .verification-message {
            margin-bottom: 15px;
            color: var(--text-color);
        }
        
        .verification-message strong {
            color: #333;
        }

        [data-theme="dark"] .verification-message strong {
            color: #ffffff;
        }
        
        /* Mensaje de spam con ícono */
        .spam-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: -2px;
            margin-bottom: 5px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        [data-theme="dark"] .spam-info {
            background-color: var(--spam-bg);
            color: var(--text-color);
        }

        [data-theme="dark"] .spam-info strong {
            color: #ffffff;
        }
        
        .spam-info i {
            color: var(--primary-color);
            margin-right: 8px;
            font-size: 1rem;
        }

        .custom-alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] .custom-alert {
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .custom-alert-danger {
            border-left: 4px solid #dc3545;
            background-color: #fff;
            color: #721c24;
            font-weight: 500;
        }

        [data-theme="dark"] .custom-alert-danger {
            background-color: #2d2d2d;
            color: #ff8080;
        }

        .custom-alert-danger i {
            color: #dc3545;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        [data-theme="dark"] .custom-alert-danger i {
            color: #ff8080;
        }
        
        /* Destaca el código específico */
        .code-highlight {
            background-color: #f8d7da;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
            font-weight: bold;
        }

        [data-theme="dark"] .code-highlight {
            background-color: var(--highlight-bg);
            color: #ff8080;
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
    </style>
</head>
<body>
    <a href="{{ route('index') }}" class="back-button">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    
    <!-- Botón para cambiar tema -->
    <button id="theme-toggle" class="theme-toggle-btn">
        <i class="bi bi-moon-fill" id="theme-icon"></i>
    </button>
    
    <div class="verification-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi Sueño <span class="dulce">Dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="logo-image">
        </div>
        

        <div class="verification-card">
            <h2 class="verification-title">Verificación</h2>
            
            @if (session('success'))
                <div class="alert alert-info">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="custom-alert custom-alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        {!! preg_replace('/\(([0-9]+)\)/', '(<span class="code-highlight">$1</span>)', session('error')) !!}
                    </div>
                </div>
            @endif

            <p class="verification-message">
                Hemos enviado un correo a <strong>{{ $email }}</strong> con el código de verificación.
            </p>            

            <form method="POST" action="{{ route('register.confirm.post') }}" id="verificationForm" novalidate>
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-4">
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

                <div class="spam-info">
                    <i class="bi bi-info-circle"></i>
                    <span>Recuerda revisar también tu carpeta de <strong>spam</strong>.</span>
                </div>

                <button type="submit" id="submitVerificationBtn" class="btn btn-verify">
                    Verificar
                </button>
                
                <div class="resend-container">
                    ¿No recibiste el código? 
                    <button type="button" id="resendCodeBtn" class="btn-link">
                        Reenviar código
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar el envío del formulario de verificación
            const verificationForm = document.getElementById('verificationForm');
            const submitButton = document.getElementById('submitVerificationBtn');
            const codeInput = document.getElementById('verification_code');
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

            if (verificationForm && submitButton) {
                let isSubmitting = false;
                
                verificationForm.addEventListener('submit', function(e) {
                    // Evitar múltiples envíos
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Verificar que el código se ha ingresado
                    if (!codeInput.value.trim()) {
                        return; // No deshabilitar si no hay código
                    }
                    
                    // Marcar como en proceso de envío
                    isSubmitting = true;
                    
                    // Deshabilitar el botón para evitar múltiples envíos
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando...';
                });
            }

            // Función para manejar el reenvío de código
            const resendButton = document.getElementById('resendCodeBtn');
            let cooldownActive = false;
            let cooldownTimer = null;

            function startCooldown(seconds) {
                if (cooldownTimer) {
                    clearInterval(cooldownTimer);
                }

                cooldownActive = true;
                resendButton.disabled = true;
                resendButton.style.opacity = '0.7';
                
                let remainingSeconds = seconds;
                resendButton.textContent = `Reenviar código en ${remainingSeconds}s`;
                
                cooldownTimer = setInterval(() => {
                    remainingSeconds--;
                    
                    if (remainingSeconds <= 0) {
                        clearInterval(cooldownTimer);
                        cooldownActive = false;
                        resendButton.disabled = false;
                        resendButton.style.opacity = '1';
                        resendButton.textContent = 'Reenviar código';
                    } else {
                        resendButton.textContent = `Reenviar código en ${remainingSeconds}s`;
                    }
                }, 1000);
            }

            if (resendButton) {
                resendButton.addEventListener('click', function() {
                    if (cooldownActive) return;
                    
                    // Mostrar un indicador de carga
                    const originalText = resendButton.textContent;
                    resendButton.disabled = true;
                    resendButton.textContent = 'Enviando...';
                    
                    // Iniciar inmediatamente el contador de 30 segundos
                    startCooldown(30);
                    
                    // Enviar la solicitud para reenviar el código
                    fetch('{{ route("register.resend-code") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: '{{ $email }}' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // El contador ya está funcionando, no necesitamos hacer nada más
                        console.log('Código reenviado con éxito');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // En caso de error, mostrar un indicador de error pero mantener el contador
                        resendButton.style.color = '#e74c3c';
                    });
                });
                
                // Comprobar si hay un temporizador activo al cargar la página
                @if(session('last_code_resent') && (time() - session('last_code_resent') < 30))
                    // Calcular los segundos restantes
                    const secondsElapsed = {{ time() - session('last_code_resent', 0) }};
                    const secondsRemaining = 30 - secondsElapsed;
                    
                    if (secondsRemaining > 0) {
                        startCooldown(secondsRemaining);
                    }
                @endif
            }

            // Mejorar entrada del código
            if (codeInput) {
                // Solo permitir números
                codeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
                });

                // Enfocar automáticamente
                codeInput.focus();
            }
        });
    </script>
</body>
</html>

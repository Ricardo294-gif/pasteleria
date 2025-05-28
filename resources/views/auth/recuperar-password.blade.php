<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Mi Sueño Dulce</title>
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

        .recovery-container {
            max-width: 450px;
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            margin-top: -20px;
            margin-left: 30px;  
        }
        
        .logo-text {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            color: #000;
            text-decoration: none;
            margin: 0;
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
            width: 33.33%;
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
        
        .auth-step.active .auth-step-text {
            color: var(--primary-color);
            font-weight: 500;
        }

        .recovery-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .recovery-card {
            background-color: var(--card-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .recovery-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: #000;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            position: relative;
        }

        [data-theme="dark"] .recovery-title {
            color: var(--title-color);
        }
        
        .recovery-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 1px;
            background-color: #000;
        }

        [data-theme="dark"] .recovery-title:after {
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
        }

        [data-theme="dark"] .form-control {
            background-color: var(--input-bg);
            color: var(--text-color);
            border-color: #444;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.02);
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

        [data-theme="dark"] p {
            color: var(--text-color);
        }

        .btn-recovery {
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
        
        .btn-recovery:hover {
            transform: translateY(-3px);
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-recovery:focus, .btn-recovery:active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

        .login-container {
            text-align: center;
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .login-link {
            color: var(--primary-color);
            text-decoration: none;
        }

        [data-theme="dark"] .login-link {
            color: var(--link-color);
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
    
    <div class="recovery-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi sueño <span class="dulce">dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Manga Pastelera" class="logo-image">
        </div>
        
        <div class="recovery-card">
            <h2 class="recovery-title">Recuperar contraseña</h2>
            
            @if (session('status'))
                <x-alert type="success" :autoDismiss="false">
                    {{ session('status') }}
                </x-alert>
            @endif

            <p class="mb-3" style="margin-top: 27px;">Introduce tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

            <form method="POST" action="{{ route('password.email') }}" id="recoveryForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico:</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" id="recoveryButton" class="btn btn-recovery">Enviar enlace</button>
                
                <div class="login-container">
                    <a href="{{ route('login') }}" class="login-link">Volver a iniciar sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recoveryForm = document.getElementById('recoveryForm');
            const recoveryButton = document.getElementById('recoveryButton');
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

            if (recoveryForm && recoveryButton) {
                let isSubmitting = false;
                
                recoveryForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email').value;

                    if (!email) {
                        return;
                    }
                    
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    isSubmitting = true;
                    recoveryButton.disabled = true;
                    recoveryButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
                });
            }
        });
    </script>
</body>
</html>

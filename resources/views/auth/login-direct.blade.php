<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Mi Sueño Dulce</title>
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

        .login-container {
            max-width: 420px;
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            margin-top: -40px;
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
            max-width: 420px;
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

        .login-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .login-card {
            background-color: var(--card-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: #000;
            text-align: center;
            margin-top: -5px;
            margin-bottom: 20px;
            text-decoration: none;
            position: relative;
        }

        [data-theme="dark"] .login-title {
            color: var(--title-color);
        }

        .login-title:after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 1px;
            background-color: #000;
        }

        [data-theme="dark"] .login-title:after {
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

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary-color);
            cursor: pointer;
        }

        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-3px);
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-login:focus, .btn-login:active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

        .forgot-link {
            text-align: center;
            display: block;
            margin: 15px 0;
            color: var(--primary-color);
            text-decoration: none;
        }

        [data-theme="dark"] .forgot-link,
        [data-theme="dark"] .register-link {
            color: var(--link-color);
        }

        .register-container {
            text-align: center;
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .register-link {
            color: var(--primary-color);
            text-decoration: none;
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

        /* Alerta flotante que se mostrará automáticamente */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            transition: all 0.5s ease;
            z-index: 1000;
            border-left: 4px solid var(--primary-color);
            min-width: 280px;
            opacity: 0;
            transform: translateY(-20px);
        }

        [data-theme="dark"] .notification-toast {
            background-color: #2d2d2d;
            color: var(--text-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
        }
        
        .notification-content i {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-right: 10px;
        }
        
        .notification-toast.show {
            opacity: 1;
            transform: translateY(0);
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
    
    <!-- Alerta flotante que se mostrará automáticamente -->
    @if (session('success'))
    <div id="notificacion" class="notification-toast">
        <div class="notification-content">
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    
    <div class="login-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi sueño <span class="dulce">dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Manga Pastelera" class="logo-image">
        </div>
        
        <div class="login-card">
            <h2 class="login-title">Iniciar sesión</h2>
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electronico:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" id="loginButton" class="btn btn-login">Iniciar Sesion</button>
                
                <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                
                <div class="register-container">
                    ¿No tienes cuenta? <a href="{{ route('register', request()->query()) }}" class="register-link">Regístrate aquí</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
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

            // Manejo de la notificación flotante
            const notificacion = document.getElementById('notificacion');
            if (notificacion) {
                // Mostrar con animación
                setTimeout(function() {
                    notificacion.classList.add('show');
                }, 100);
                
                // Ocultar después de 5 segundos
                setTimeout(function() {
                    notificacion.classList.remove('show');
                    // Eliminar completamente después de la animación
                    setTimeout(function() {
                        notificacion.remove();
                    }, 500);
                }, 5000);
            }

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            if (loginForm && loginButton) {
                let isSubmitting = false;
                
                loginForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;

                    if (!email || !password) {
                        return;
                    }
                    
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    isSubmitting = true;
                    loginButton.disabled = true;
                    loginButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iniciando sesión...';
                });
            }
        });
    </script>
</body>
</html>


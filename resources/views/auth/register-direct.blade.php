<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Mi Sueño Dulce</title>
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

        .register-container {
            max-width: 500px;
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            margin-top: 0px;
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
        
        .logo-text i {
            font-size: 1.8rem;
            margin-left: 5px;
            transform: rotate(10deg);
            display: inline-block;
        }
        
        .logo-image {
            max-width: 50px;
            margin-left: 10px;
        }

        .register-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            margin-bottom: 70px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .register-card {
            background-color: var(--card-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .register-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: #000;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            position: relative;
            margin-top: -10px;
        }

        [data-theme="dark"] .register-title {
            color: var(--title-color);
        }

        .register-title:after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 1px;
            background-color: #000;
        }

        [data-theme="dark"] .register-title:after {
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

        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-3px);
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-register:focus, .btn-register:active {
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
        
        /* Eliminar íconos de error pero mantener el borde rojo */
        .form-control.is-invalid {
            background-image: none;
        }
        
        /* Estilo para mensajes de error */
        .text-danger {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        [data-theme="dark"] .text-danger {
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
    
    <div class="register-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi Sueño <span class="dulce">Dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="logo-image">
        </div>

        <div class="register-card">
            <h2 class="register-title">Registro</h2>
            
            <form method="POST" action="{{ route('register.verify', request()->query()) }}" id="registerForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellidos:</label>
                        <input type="text" name="apellido" id="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" required>
                        @error('apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <div class="text-danger">{{ $message }}</div>
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
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Confirmar contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" id="registerButton" class="btn btn-register">Registrarme</button>
                
                <div class="login-container">
                    ¿Ya tienes cuenta? <a href="{{ route('login', request()->query()) }}" class="login-link">Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const registerButton = document.getElementById('registerButton');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password-confirm');
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

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            if (registerForm && registerButton) {
                let isSubmitting = false;
                
                registerForm.addEventListener('submit', function(e) {
                    const name = document.getElementById('name').value;
                    const apellido = document.getElementById('apellido').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password-confirm').value;

                    if (!name || !apellido || !email || !password || !confirmPassword) {
                        return;
                    }
                    
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    isSubmitting = true;
                    registerButton.disabled = true;
                    registerButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
                });
            }
        });
    </script>
</body>
</html>


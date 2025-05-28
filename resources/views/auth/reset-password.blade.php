<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Mi Sueño Dulce</title>
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

        .reset-container {
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
        
        .logo-text .dulce {
            color: #ff7070;
        }
        
        .logo-image {
            max-width: 50px;
            margin-left: 10px;
        }

        .reset-card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            padding: 25px;
            padding-bottom: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .reset-title {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: #000;
            text-align: center;
            margin-top: -5px;
            margin-bottom: 20px;
            text-decoration: none;
            position: relative;
        }

        .reset-title:after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 1px;
            background-color: #000;
        }

        .form-label {
            font-weight: normal;
            margin-bottom: 8px;
            color: #666;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            background-color: white;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.02);
        }

        /* Eliminar el fondo azul celeste en autocompletar */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            transition: background-color 5000s ease-in-out 0s;
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

        .btn-reset {
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
        
        .btn-reset:hover {
            transform: translateY(-3px);
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-reset:focus, .btn-reset:active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

        .login-link {
            text-align: center;
            display: block;
            margin: 15px 0;
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

        /* Estilo para mensajes de error */
        .text-danger {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Borde rojo para campos con error pero sin íconos */
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }
    </style>
</head>
<body>
    <a href="{{ route('index') }}" class="back-button">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    
    <!-- Alerta flotante que se mostrará automáticamente -->
    @if(session('error'))
    <div id="notificacion" class="notification-toast">
        <div class="notification-content">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif
    
    <!-- Nueva alerta flotante para errores de validación generales -->
    @if($errors->any() && !$errors->has('password') && !$errors->has('email'))
    <div id="errores-generales" class="notification-toast">
        <div class="notification-content">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    </div>
    @endif
    
    <div class="reset-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi sueño <span class="dulce">dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Manga Pastelera" class="logo-image">
        </div>
        
        <div class="reset-card">
            <h2 class="reset-title">Restablecer Contraseña</h2>
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required readonly>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-reset">Restablecer Contraseña</button>
                
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="login-link">Volver a iniciar sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('password_confirmation');

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
            
            // Mostrar errores generales
            const erroresGenerales = document.getElementById('errores-generales');
            if (erroresGenerales) {
                // Mostrar con animación
                setTimeout(function() {
                    erroresGenerales.classList.add('show');
                }, 100);
                
                // Ocultar después de 5 segundos
                setTimeout(function() {
                    erroresGenerales.classList.remove('show');
                    // Eliminar completamente después de la animación
                    setTimeout(function() {
                        erroresGenerales.remove();
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

            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>
</html>

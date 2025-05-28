<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cambio de Correo - Mi Sueño Dulce</title>
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
            text-align: center;
            letter-spacing: 3px;
            font-size: 1.2rem;
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

        .alert {
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            background-color: rgba(255, 112, 112, 0.05);
            padding: 15px;
            margin-bottom: 20px;
        }

        .alert-info {
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
        /* Mensaje de verificación */
        .verification-message {
            margin-bottom: 15px;
            color: var(--text-color);
        }
        
        .verification-message strong {
            color: #333;
        }
                /* Mensaje de spam con ícono */
                .spam-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 8px;
            margin-bottom: -10px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .spam-info i {
            color: var(--primary-color);
            margin-right: 8px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <button onclick="history.back()" class="back-button">
        <i class="bi bi-arrow-left"></i> Volver
    </button>
    
    <div class="verification-container">
        <div class="logo-container">
            <h1 class="logo-text">Mi sueño <span class="dulce">dulce</span></h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo Manga Pastelera" class="logo-image">
        </div>
        
        <div class="verification-card">
            <h2 class="verification-title">Verificar correo</h2>
            
            <p class="verification-message">
                Hemos enviado un correo a <strong>{{ $new_email }}</strong> con el código de verificación.
            </p>            

            
            <form action="{{ route('perfil.verificar-email.post') }}" method="POST">
                @csrf
                <input type="hidden" name="new_email" value="{{ $new_email }}">
                
                <div class="mb-3">
                    <label for="verification_code" class="form-label">Código de verificación:</label>
                    <input type="text" name="verification_code" id="verification_code" class="form-control @error('verification_code') is-invalid @enderror" maxlength="6" required autofocus>
                    @error('verification_code')
                        <div class="invalid-feedback text-center">{{ $message }}</div>
                    @enderror
                </div>
                <i class="bi bi-info-circle"></i>
                        <span>Recuerda revisar también tu carpeta de <strong>spam</strong>.</span>
                    </div>
                <button type="submit" id="verifyButton" class="btn btn-verify">Verificar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifyForm = document.querySelector('form');
            const verifyButton = document.getElementById('verifyButton');
            
            if (verifyForm && verifyButton) {
                verifyForm.addEventListener('submit', function(e) {
                    // Deshabilitar el botón para evitar múltiples envíos
                    verifyButton.disabled = true;
                    verifyButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando...';

                    // Permitir que el formulario se envíe
                    return true;
                });
            }
        });
    </script>
</body>
</html>

@extends('layouts.layout')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="login-container py-5">
    <div class="container">
        <!-- Botón Volver -->
        <div class="mb-4">
            <a href="{{ route('index') }}" style="color: #ff7070; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                <i class="bi bi-arrow-left" style="margin-right: 5px;"></i> Volver a la página principal
            </a>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="login-card">
                    <div class="login-card-header">
                        <h2>¡Bienvenido de nuevo!</h2>
                        <p>Inicia sesión para continuar tu dulce experiencia</p>
                    </div>
                    
                    <div class="login-card-body">
                        <div class="decorative-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-floating mb-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@ejemplo.com">
                                <label for="email">Correo Electrónico</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                                <label for="password">Contraseña</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-login">
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="login-card-footer">
                        <p>¿No tienes una cuenta? <a href="{{ route('register') }}" class="register-link">Regístrate</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-container {
        background-color: #fef6e6;
        min-height: 80vh;
        padding: 40px 0;
    }
    
    .login-card {
        background-color: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(255, 112, 112, 0.15);
        position: relative;
    }
    
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(to right, #ff9e9e, #ff7070);
    }
    
    .login-card-header {
        padding: 30px;
        text-align: center;
        color: #333;
        background-color: #fff;
    }
    
    .login-card-header h2 {
        font-family: 'Dancing Script', cursive;
        font-size: 2.5rem;
        color: #ff7070;
        margin-bottom: 10px;
    }
    
    .login-card-header p {
        color: #888;
        font-size: 1rem;
    }
    
    .login-card-body {
        padding: 0 40px 30px;
        position: relative;
    }
    
    .decorative-icon {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .decorative-icon i {
        font-size: 3.5rem;
        color: #ff7070;
        opacity: 0.8;
    }
    
    .form-floating {
        margin-bottom: 20px;
    }
    
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 2px solid #eee;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #ff7070;
        box-shadow: 0 0 0 0.25rem rgba(255, 112, 112, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #ff7070;
        border-color: #ff7070;
    }
    
    .btn-login {
        background: linear-gradient(to right, #ff9e9e, #ff7070);
        border: none;
        color: white;
        padding: 12px 0;
        font-size: 1.1rem;
        border-radius: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 112, 112, 0.2);
        background: linear-gradient(to right, #ff7070, #ff5050);
    }
    
    .login-card-footer {
        text-align: center;
        padding: 20px 30px;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }
    
    .register-link {
        color: #ff7070;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .register-link:hover {
        color: #ff5050;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .login-card-body {
            padding: 0 20px 20px;
        }
    }
</style>
@endsection

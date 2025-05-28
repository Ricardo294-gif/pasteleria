@extends('layouts.layout')

@section('title', 'Registro')

@section('content')
<div class="register-container py-5">
    <div class="container">
        <!-- Botón Volver -->
        <div class="mb-4">
            <a href="{{ route('index') }}" style="color: #ff7070; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                <i class="bi bi-arrow-left" style="margin-right: 5px;"></i> Volver a la página principal
            </a>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="register-card">
                    <div class="register-card-header">
                        <h2>¡Únete a nuestra dulce comunidad!</h2>
                        <p>Regístrate para descubrir un mundo de sabores</p>
                    </div>
                    
                    <div class="register-card-body">
                        <div class="decorative-elements">
                            <div class="decorative-element" id="cupcake">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <div class="decorative-element" id="cake">
                                <i class="bi bi-cake2"></i>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Tu nombre">
                                        <label for="name">Nombre</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" placeholder="Tu apellido">
                                        <label for="apellido">Apellido</label>
                                        @error('apellido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-4">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nombre@ejemplo.com">
                                        <label for="email">Correo Electrónico</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Contraseña">
                                        <label for="password">Contraseña</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña">
                                        <label for="password-confirm">Confirmar Contraseña</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="#" class="terms-link">términos y condiciones</a>
                                </label>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-register">
                                    Crear mi cuenta
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="register-card-footer">
                        <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="login-link">Iniciar Sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .register-container {
        background-color: #fef6e6;
        min-height: 80vh;
        padding: 40px 0;
        position: relative;
        overflow: hidden;
    }
    
    .register-container::before {
        content: '';
        position: absolute;
        top: -150px;
        right: -150px;
        width: 300px;
        height: 300px;
        background: rgba(255, 158, 158, 0.1);
        border-radius: 50%;
        z-index: 0;
    }
    
    .register-container::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 300px;
        height: 300px;
        background: rgba(255, 158, 158, 0.1);
        border-radius: 50%;
        z-index: 0;
    }
    
    .register-card {
        background-color: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(255, 112, 112, 0.15);
        position: relative;
        z-index: 1;
    }
    
    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(to right, #ff9e9e, #ff7070);
    }
    
    .register-card-header {
        padding: 30px;
        text-align: center;
        color: #333;
        background-color: #fff;
    }
    
    .register-card-header h2 {
        font-family: 'Dancing Script', cursive;
        font-size: 2.5rem;
        color: #ff7070;
        margin-bottom: 10px;
    }
    
    .register-card-header p {
        color: #888;
        font-size: 1rem;
    }
    
    .register-card-body {
        padding: 0 40px 30px;
        position: relative;
    }
    
    .decorative-elements {
        position: relative;
        height: 60px;
        margin-bottom: 20px;
    }
    
    .decorative-element {
        position: absolute;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        animation: float 3s ease-in-out infinite;
    }
    
    .decorative-element i {
        font-size: 2rem;
    }
    
    #cupcake {
        background: linear-gradient(135deg, #ff9e9e, #ff7070);
        top: 0;
        left: 30%;
        animation-delay: 0.5s;
    }
    
    #cake {
        background: linear-gradient(135deg, #ff7070, #ff5050);
        top: 10px;
        right: 30%;
    }
    
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0px);
        }
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
    
    .terms-link {
        color: #ff7070;
        text-decoration: none;
        font-weight: 600;
    }
    
    .btn-register {
        background: linear-gradient(to right, #ff9e9e, #ff7070);
        border: none;
        color: white;
        padding: 12px 0;
        font-size: 1.1rem;
        border-radius: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 112, 112, 0.2);
        background: linear-gradient(to right, #ff7070, #ff5050);
    }
    
    .register-card-footer {
        text-align: center;
        padding: 20px 30px;
        background-color: #f9f9f9;
        border-top: 1px solid #eee;
    }
    
    .login-link {
        color: #ff7070;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .login-link:hover {
        color: #ff5050;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .register-card-body {
            padding: 0 20px 20px;
        }
        
        .decorative-element {
            display: none;
        }
    }
</style>
@endsection

@extends('layouts.layout')

@section('title', 'Mi Perfil')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Mi Perfil</h1>

    @if(session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header text-white">
                    <h4 class="mb-0">Información Personal</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.actualizar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido', auth()->user()->apellido) }}" required>
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Información</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white">
                    <h4 class="mb-0">Cambiar Contraseña</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.password') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white">
                    <h4 class="mb-0">Mis Compras Recientes</h4>
                </div>
                <div class="card-body">
                    <p>Aquí puedes ver el historial de tus compras recientes.</p>
                    <!-- Aquí se mostraría un historial de compras (podría implementarse en el futuro) -->
                    <x-alert type="info" :dismissible="false">
                        No hay compras recientes para mostrar.
                    </x-alert>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
        background-color: #ce1212 !important;
    }

    .btn-primary {
        background-color: #ce1212;
        border-color: #ce1212;
    }

    .btn-primary:hover {
        background-color: #a70000;
        border-color: #a70000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Evitar desplazamiento automático cuando se recarga la página
        if (window.location.hash) {
            // Esperar un momento y luego desplazar a la parte superior
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'auto'
                });
            }, 0);
        }
    });
</script>
@endsection

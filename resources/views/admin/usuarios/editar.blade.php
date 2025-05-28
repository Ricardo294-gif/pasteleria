@extends('admin.layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
    <style>
    /* Estilos para alertas flotantes */
    .floating-alerts {
            position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        }

    .floating-alert {
            margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
        opacity: 0;
        transform: translateX(100%);
        animation: slideIn 0.5s ease forwards;
        }

    @keyframes slideIn {
        to {
            opacity: 1;
                transform: translateX(0);
            }
        }

        .form-label {
            font-weight: 500;
        color: #333;
        }

    .form-control:focus, .form-select:focus {
        border-color: #ff7070;
        box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

    .btn-primary {
        background-color: #ff7070;
        border-color: #ff7070;
        }

    .btn-primary:hover {
        background-color: #ff5656;
        border-color: #ff5656;
        }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        }
    </style>

<div class="container-fluid">
    <!-- Encabezado -->
    <div class="page-header">
        <h1 class="page-title">Editar Usuario</h1>
        <p class="text-muted">Modifica los detalles del usuario.</p>
    </div>

    <div class="floating-alerts">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Formulario -->
                <div class="card">
                    <div class="card-header">
            <i class="bi bi-person-gear me-2"></i>Información del Usuario
                    </div>
                    <div class="card-body">
            <form action="{{ route('admin.usuarios.actualizar', $usuario->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                
                <div class="row">
                                <div class="col-md-6">
                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                        <!-- Apellido -->
                        <div class="mb-4">
                            <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                                   id="apellido" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
                                    @error('apellido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                            </div>

                    <div class="col-md-6">
                        <!-- Teléfono -->
                        <div class="mb-4">
                                <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <!-- Dirección -->
                        <div class="mb-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                      id="direccion" name="direccion" rows="3">{{ old('direccion', $usuario->direccion) }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>

                        <!-- Rol de administrador -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input @error('is_admin') is-invalid @enderror" 
                                       id="is_admin" name="is_admin" value="1" 
                                       {{ old('is_admin', $usuario->is_admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">
                                    Permisos de Administrador
                                </label>
                                @error('is_admin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Al activar esta opción, el usuario tendrá acceso al panel de administración.</div>
                        </div>
                    </div>
                            </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                </button>
                                <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-2"></i>Cancelar
                                </a>
                            </div>
                        </form>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
                });
        });
    </script>
@endpush
@endsection

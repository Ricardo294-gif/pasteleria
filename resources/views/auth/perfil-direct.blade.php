@extends('layouts.layout')

@section('title', 'Mi Perfil - Mi Sueño Dulce')

@section('content')
    <style>
        :root {
            --primary-color: #ff7070;
        --bg-color: #fef6e6;
        --text-color: #333;
        --heading-color: #222;
        --border-color: #eee;
        --card-bg: #fff;
        --sidebar-bg: #fff;
        --hover-color: #f9f9f9;
        --transition: all 0.25s ease;
    }

    /* Estilos para alertas flotantes a la derecha */
    .profile-alerts-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: auto;
        max-width: 350px;
        margin: 0;
        padding: 0;
    }

    .custom-alert.profile-alert {
        width: 100%;
        margin: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        right: 0;
    }

    /* Mejora estética para los iconos de ojo al estilo del login */
    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        border: none;
        background: transparent !important;
        color: var(--primary-color);
        cursor: pointer;
        padding: 0;
    }

    .password-toggle:hover {
        color: #e56464;
    }

    .password-toggle i {
        font-size: 1.2rem;
    }

    .password-container .form-control {
        padding-right: 40px;
    }
    
    /* Asegurar que los mensajes de error se muestren correctamente */
    .invalid-feedback {
        display: block;
        width: 100%;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 4px;
    }
    
    /* Eliminar el icono de error en los campos de nueva contraseña y confirmar contraseña */
    #password.is-invalid,
    #password_confirmation.is-invalid {
        background-image: none !important;
        padding-right: 40px !important; /* Mantener espacio para el icono del ojo */
    }

    .page-container {
        max-width: 1200px;
        width: 100%;
        margin: 2rem auto;
        padding: 0;
    }

    .main-content {
        display: flex;
        min-height: calc(100vh - 280px);
        position: relative;
    }

    .sidebar {
        width: 250px;
        background-color: transparent;
        border-right: 1px solid var(--border-color);
        padding: 1.5rem 0;
        position: static;
        height: auto;
        overflow-y: visible;
    }

    .content-area {
        flex: 1;
        padding: 1.5rem;
        overflow-x: hidden;
        margin-left: 0;
        min-height: calc(100vh - 280px);
    }

    .user-info {
        text-align: center;
        padding: 0 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color:rgb(255, 255, 255);
            display: flex;
            align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: var(--primary-color);
        font-size: 2.5rem;
        border: 1px solid var(--border-color);
    }

    .user-name {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 3px;
    }

    .user-email {
        font-size: 0.8rem;
        color: #777;
    }

    .nav-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin-bottom: 5px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: var(--text-color);
        text-decoration: none;
            font-weight: 500;
        font-size: 0.9rem;
        border-left: 3px solid transparent;
        transition: var(--transition);
        background-color: transparent;
    }

    .nav-link:hover {
        background-color: transparent;
        color: var(--primary-color);
        border-left-color: var(--primary-color);
    }

    .nav-link.active {
        background-color: transparent;
        color: var(--primary-color);
        border-left-color: var(--primary-color);
        font-weight: 600;
    }

    .nav-link i {
        margin-right: 12px;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .section {
        display: none;
        background-color: var(--card-bg);
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
        animation: fadeIn 0.3s ease forwards;
        padding-bottom: 0px;
        padding-top: 0px;
    }

    .section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .section-icon {
        color: var(--primary-color);
        font-size: 2.3em;
        margin-left: 20px;
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-top: 8px;
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
    }

    .section-title {
        font-weight: 700;
        font-size: 1.7rem;
        margin-top: 0px;
        margin-bottom: -10px;
        padding: 20px;
        color: var(--heading-color);
    }

    .section-body {
        padding: 3rem;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 4px;
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--border-color);
        background-color: #f9f9f9;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 2px rgba(255, 112, 112, 0.25);
        border-color: var(--primary-color);
        background-color: #fff;
    }

    .btn {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
            font-weight: 500;
        letter-spacing: 0.02em;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        box-shadow: 0 2px 4px rgba(255, 112, 112, 0.25);
    }

    .btn-primary:hover {
        background-color: #e56464;
        transform: translateY(-1px);
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .alert {
        border-radius: 4px;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }

    /* Estilos mejorados para historial de compras */
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    .order-card {
        background-color: white;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.2rem;
        background-color: #fafafa;
        border-bottom: 1px solid var(--border-color);
    }

    .order-info {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .order-status {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 12px;
    }

    .order-status.status-pendiente { background-color: #ffaa00; }
    .order-status.status-confirmado { background-color: #17a2b8; }
    .order-status.status-en_proceso { background-color: #007bff; }
    .order-status.status-terminado { background-color: #28a745; }
    .order-status.status-recogido { background-color: #6f42c1; }
    .order-status.status-cancelado { background-color: #dc3545; }

    .order-number {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 3px;
    }

    .order-date {
        font-size: 0.75rem;
        color: #777;
    }

    .order-total {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.05rem;
        white-space: nowrap;
        margin-left: 10px;
    }

    .order-body {
        padding: 1.2rem;
    }

    .order-status-badge {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.6rem;
        border-radius: 30px;
        white-space: nowrap;
        color: var(--primary-color);
    }

    .order-header .order-status-badge {
        margin-bottom: 0;
        margin-right: 10px;
        font-size: 1rem;
        padding: 0.2rem 0.5rem;
    }

    .status-pendiente {
        background-color: rgba(255, 170, 0, 0.1);
        color: #ffaa00;
    }

    .status-confirmado {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .status-en_proceso {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }

    .status-terminado {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .status-recogido {
        background-color: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
    }

    .status-cancelado {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .order-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
        align-items: center;
        justify-content: center;
    }
    
    .btn-action {
        background-color: #ff7070;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-family: 'Dancing Script', cursive;
        line-height: 1.5;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 112, 112, 0.2);
        color: white;
        text-decoration: none;
    }
    
    .btn-action.btn-outline {
        background-color: transparent;
        color: #ff7070;
        border: 1px solid #ff7070;
    }
    
    .btn-action i {
        margin-right: 5px;
    }

    .order-products {
        border-top: none; 
        padding-top: 0;
        margin-top: 0;
    }
    
    .order-products-title {
        font-size: 1rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 10px;
    }
    
    .order-total-summary {
        font-weight: 500;
        padding: 8px 0;
    }
    
    .total-label {
        color: #555;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    
    .total-value {
        color: var(--primary-color);
        font-size: 1.1rem;
        font-weight: 600;
    }

    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        font-size: 0.85rem;
    }

    .product-item:not(:last-child) {
        border-bottom: 1px dashed var(--border-color);
    }

    .product-price {
        font-weight: 600;
        color: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #777;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    @media (max-width: 991.98px) {
        .main-content {
            flex-direction: row; /* Mantener dirección horizontal */
        }
        
        .sidebar {
            width: 220px;
            position: static;
            border-right: 1px solid var(--border-color);
            border-bottom: none;
            padding: 1rem;
        }
        
        .content-area {
            padding: 1rem;
            margin-left: 0;
        }
        
        .user-info {
            flex-direction: column;
            text-align: center;
            padding: 0 0 1rem;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            margin: 0 auto 0.75rem;
            width: 64px;
            height: 64px;
            font-size: 2rem;
        }
        
        .nav-menu {
            display: block;
        }
        
        .nav-item {
            margin-bottom: 5px;
            width: 100%;
        }
        
        .nav-link {
            padding: 0.6rem 1rem;
            justify-content: flex-start;
            text-align: left;
            border-left: 3px solid transparent;
            border-radius: 4px;
            border: none;
        }
        
        .nav-link.active {
            border-left-color: var(--primary-color);
        }
        
        .nav-link i {
            margin-right: 10px;
        }
    }

    @media (max-width: 767.98px) {
        .main-content {
            flex-direction: column; /* En móviles cambiar a columna */
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
            border-right: none;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
        }
        
        .nav-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .nav-item {
            flex: 1 0 calc(50% - 0.25rem);
            margin-bottom: 0;
        }
        
        .nav-link {
            padding: 0.5rem;
            justify-content: center;
            text-align: center;
            border-left: none;
            border: 1px solid var(--border-color);
        }
        
        .nav-link i {
            margin-right: 5px;
        }
        
        .content-area {
            padding: 1rem;
            margin-left: 0;
        }
        
        .section-header, .section-body {
            padding: 2rem;
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .order-total {
            margin-top: 0.5rem;
            margin-left: 22px;
        }
        }
    </style>

<div class="page-container">
    <!-- Contenedor de alertas flotante -->
    <div class="profile-alerts-container">
        @if(session('success'))
            <div class="custom-alert custom-alert-success profile-alert auto-dismiss">
                <div class="icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="content">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="custom-alert custom-alert-danger profile-alert auto-dismiss">
                <div class="icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="content">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="custom-alert custom-alert-warning profile-alert auto-dismiss">
                <div class="icon">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <div class="content">
                    {{ session('warning') }}
                </div>
                <button type="button" class="btn-close" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="custom-alert custom-alert-info profile-alert auto-dismiss">
                <div class="icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div class="content">
                    {{ session('info') }}
                </div>
                <button type="button" class="btn-close" aria-label="Cerrar"></button>
            </div>
        @endif
    </div>

    <div class="main-content">
        <div class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }} {{ auth()->user()->apellido }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            
            <ul class="nav-menu" id="profileTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" href="#info">
                                        <i class="bi bi-person-circle"></i> Mis Datos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pedidos-tab" href="#pedidos">
                                        <i class="bi bi-box-seam"></i> Mis Pedidos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="security-tab" href="#security">
                                        <i class="bi bi-shield-lock"></i> Seguridad
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="delete-tab" href="#delete">
                                        <i class="bi bi-trash"></i> Eliminar Cuenta
                                    </a>
                                </li>
                            </ul>
                    </div>

        <div class="content-area">
            <!-- Información Personal -->
            <div class="section active" id="info">
                <div class="section-header">
                    <h2 class="section-title">Información Personal</h2>
                    <i class="bi bi-person-vcard section-icon"></i>
                                </div>
                <div class="section-body">
                    <form action="{{ route('perfil.actualizar') }}" method="POST" id="form-actualizar">
                        @csrf
                        <input type="hidden" name="active_tab" value="info">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                            <div class="col-md-6 mb-3">
                                            <label for="apellido" class="form-label">Apellido</label>
                                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido', auth()->user()->apellido) }}" required>
                                            @error('apellido')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                        <div class="mb-4">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', auth()->user()->telefono) }}">
                                            @error('telefono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Actualizar Información
                                            </button>
                                        </div>
                                    </form>
                            </div>
                        </div>

                        <!-- Seguridad -->
            <div class="section" id="security">
                <div class="section-header">
                    <h2 class="section-title">Cambiar Contraseña</h2>
                    <i class="bi bi-shield-lock section-icon"></i>
                </div>
                <div class="section-body">
                    <form method="POST" action="{{ route('perfil.password') }}" id="form-password">
                        @csrf
                        <input type="hidden" name="active_tab" value="security">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual *</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña *</label>
                            <div class="password-container position-relative">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required>
                                <button type="button" class="password-toggle" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña *</label>
                            <div class="password-container position-relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" minlength="8" required>
                                <button type="button" class="password-toggle" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key me-2"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

                        <!-- Eliminar Cuenta -->
            <div class="section" id="delete">
                <div class="section-header">
                    <h2 class="section-title text-danger">Eliminar Mi Cuenta</h2>
                    <i class="bi bi-exclamation-triangle section-icon text-danger"></i>
                                </div>
                <div class="section-body">
                <div class="text-danger mb-4 p-3 border border-danger rounded" style="margin-top:-10px;">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <strong>¡Atención!</strong> Esta acción es irreversible. Al eliminar tu cuenta:
                                        <ul class="mb-0 mt-2">
                                            <li>Se eliminarán tus datos personales</li>
                                            <li>Perderás el acceso a tu historial de pedidos</li>
                                            <li>No podrás recuperar tu cuenta posteriormente</li>
                                        </ul>
                                    </div>
                    <form method="POST" action="{{ route('perfil.eliminar-cuenta') }}" id="delete-account-form" onsubmit="return false;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="active_tab" value="delete">
                                        <div class="mb-3">
                                                <label for="delete_password" class="form-label">Introduce tu Contraseña para Confirmar *</label>
                                                <input type="password" name="password" id="delete_password" class="form-control @error('password') is-invalid @enderror" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                        </div>

                        <div class="d-grid">
                            <button type="button" id="show-delete-modal" class="btn-action btn-outline" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid #dc3545; font-family: 'Dancing Script', cursive; padding: 13px 18px; font-size: 1.1rem; width: 100%;">
                                <i class="bi bi-trash"></i> Eliminar mi cuenta permanentemente
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mis Pedidos -->
            <div class="section" id="pedidos">
                <div class="section-header">
                    <h2 class="section-title">Historial de Compras</h2>
                    <i class="bi bi-bag-check section-icon"></i>
                </div>
                <div class="section-body">
                    @if(count($compras) > 0)
                        <div class="mb-4" id="pedidos-info-alert" style="display: none;">
                            <x-alert type="info" class="mb-3" style="width: 100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Nota:</strong> Solo puedes cancelar pedidos en estado "Pagado".
                                    </div>
                                    <button type="button" id="close-info-alert" class="btn-close btn-close-sm" style="font-size: 0.7rem;" aria-label="Cerrar"></button>
                                </div>
                            </x-alert>
                        </div>

                        <div class="orders-list">
                            @foreach($compras as $compra)
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-info">
                                        <div class="d-flex align-items-center">
                                            
                                            <div>
                                                <div class="order-number">Pedido #{{ $compra->codigo ?? $compra->id }}</div>
                                                <div class="order-date">{{ $compra->created_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-status-badge status-{{ strtolower($compra->estado) }} me-2">
                                                @switch($compra->estado)
                                                    @case('confirmado')
                                                        <i class="bi bi-check-circle-fill me-1"></i> Aceptado
                                                        @break
                                                    @case('en_proceso')
                                                        <i class="bi bi-gear-fill me-1"></i> Elaborando
                                                        @break
                                                    @case('terminado')
                                                        <i class="bi bi-bag-check-fill me-1"></i> Listo para Recoger
                                                        @break
                                                    @case('recogido')
                                                        <i class="bi bi-check2-all me-1"></i> Recogido
                                                        @break
                                                    @case('cancelado')
                                                        <i class="bi bi-x-circle-fill me-1"></i> Cancelado
                                                        @break
                                                    @default
                                                        <i class="bi bi-hourglass-split me-1"></i> Pendiente
                                                @endswitch
                                            </div>
                                </div>

                                <div class="order-body">
                                    <div class="order-products">
                                        <h6 class="order-products-title mb-2">Productos</h6>
                                        @foreach($compra->detalles->take(2) as $detalle)
                                            <div class="product-item">
                                                <div>{{ $detalle->nombre_producto }} <span class="text-muted">({{ $detalle->cantidad }})</span></div>
                                                <div class="product-price">€{{ number_format($detalle->subtotal, 2) }}</div>
                                            </div>
                                        @endforeach
                                        
                                        @if(count($compra->detalles) > 2)
                                            <div class="text-center mt-2">
                                                <a href="{{ route('perfil.pedido.detalle', $compra->id) }}" class="text-primary">
                                                    <small>+ {{ count($compra->detalles) - 2 }} productos más</small>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="order-total-summary mt-3 text-end">
                                            <span class="total-label">Total del pedido:</span>
                                            <span class="total-value">€{{ number_format($compra->total, 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="order-actions mt-3 pt-3 border-top">
                                        @if($compra->estado == 'pagado')
                                        <form action="{{ route('perfil.cancelar-pedido', $compra->id) }}" method="POST" class="cancel-order-form d-inline-block me-2" id="cancel-form-{{ $compra->id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn-action btn-outline show-cancel-modal" data-id="{{ $compra->id }}" style="font-family: 'Dancing Script', cursive; padding: 13px 18px; font-size: 1.1rem;">
                                                <i class="bi bi-x-circle"></i> Cancelar
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('perfil.pedido.detalle', $compra->id) }}" class="btn-action" style="padding: 13px 18px; font-family: 'Dancing Script', cursive; font-size: 1.1rem;">
                                            <i class="bi bi-eye"></i> Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-bag-x"></i>
                            <h5>No tienes compras realizadas</h5>
                            <p>Tus compras aparecerán aquí una vez hayas realizado algún pedido.</p>
                            <a href="{{ route('index') }}#menu" class="btn btn-primary mt-3" style="font-size: 1.2rem; padding: 0.8rem 1.5rem;">
                                <i class="bi bi-basket me-2" style="font-size: 1.4rem; color: white;"></i>Ver productos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar cuenta - Reubicado fuera de cualquier sección -->
    <div class="delete-account-modal" id="delete-account-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1050; justify-content: center; align-items: center;">
        <div class="modal-content" style="background-color: white; border-radius: 10px; max-width: 400px; width: 90%; padding: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); text-align: center; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div style="color: #ff7070; font-size: 3rem; margin-bottom: 15px;">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <h3 style="font-family: 'Dancing Script', cursive; color: #333; margin-bottom: 15px; font-size: 1.8rem;">
                ¿Estás seguro?
            </h3>
            <p style="font-size: 1.1rem; margin-bottom: 25px; color: #666;">
                Esta acción es <strong>irreversible</strong>. Tu cuenta y datos personales serán eliminados permanentemente.
            </p>
            
            <div style="margin-top: 30px; margin-bottom: 15px;">
                <!-- Botón de mantener presionado con barra de progreso -->
                <div style="position: relative; width: 100%; height: 50px; margin-top: 10px; overflow: hidden; border-radius: 25px; margin-bottom: 20px;">
                    <!-- Barra de progreso -->
                    <div id="hold-progress" style="position: absolute; left: 0; top: 0; height: 100%; width: 0%; background-color: rgba(255, 112, 112, 0.3); transition: width 0.05s; border-radius: 25px;"></div>
                    
                    <!-- Botón para mantener -->
                    <button id="hold-to-confirm" style="position: relative; width: 100%; height: 100%; background-color: rgba(255, 112, 112, 0.1); border: 1px solid rgba(255, 112, 112, 0.3); border-radius: 25px; color: #ff7070; font-family: 'Dancing Script', cursive; font-size: 1.2rem; cursor: pointer; z-index: 2; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-hand-index-thumb me-2"></i>Mantén presionado para confirmar
                    </button>
                </div>
                
                <!-- Botón para cancelar -->
                <button id="cancel-delete" style="padding: 10px 20px; width: 100%; background: transparent; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; color: #666; font-weight: 600; font-family: 'Dancing Script', cursive; font-size: 1.1rem;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para cancelar pedido -->
    <div class="cancel-order-modal" id="cancel-order-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1050; justify-content: center; align-items: center;">
        <div class="modal-content" style="background-color: white; border-radius: 10px; max-width: 400px; width: 90%; padding: 25px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); text-align: center; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div style="color: #ff7070; font-size: 3rem; margin-bottom: 15px;">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <h3 style="font-family: 'Dancing Script', cursive; color: #333; margin-bottom: 15px; font-size: 1.8rem;">
                ¿Estás seguro?
            </h3>
            <p style="font-size: 1.1rem; margin-bottom: 25px; color: #666;">
                ¿Realmente deseas cancelar este pedido? Esta acción no se puede deshacer.
            </p>
            
            <div style="margin-top: 30px; margin-bottom: 15px;">
                <!-- Botones de acción -->
                <div style="display: flex; flex-direction: row; justify-content: space-between; gap: 10px;">
                    <button id="close-cancel-modal" style="padding: 10px 15px; width: 48%; background: transparent; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; color: #666; font-weight: 600; font-family: 'Dancing Script', cursive; font-size: 1.1rem;">
                        Volver
                    </button>
                    
                    <button id="confirm-cancel" style="padding: 10px 15px; width: 48%; background-color: #ff7070; border: none; border-radius: 10px; cursor: pointer; color: white; font-weight: 600; font-family: 'Dancing Script', cursive; font-size: 1.1rem;">
                        <i class="bi bi-check-circle me-1"></i>Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navegación por pestañas
            const tabLinks = document.querySelectorAll('#profileTabs .nav-link');
            const tabContents = document.querySelectorAll('.section');

            function showTab(tabId) {
                // Ocultar todos los contenidos de pestañas
                tabContents.forEach(function(content) {
                    content.classList.remove('active');
                });

                // Desactivar todos los enlaces de pestañas
                tabLinks.forEach(function(link) {
                    link.classList.remove('active');
                });

                // Mostrar el contenido de la pestaña seleccionada
                const tabContent = document.getElementById(tabId);
                if (tabContent) {
                    tabContent.classList.add('active');
                } else {
                    // Fallback a la primera pestaña
                    if (tabContents.length > 0) {
                        tabContents[0].classList.add('active');
                    }
                }

                // Activar el enlace de la pestaña seleccionada
                const tabLink = document.querySelector(`a[href="#${tabId}"]`);
                if (tabLink) {
                    tabLink.classList.add('active');
                } else {
                    // Fallback al primer enlace
                    if (tabLinks.length > 0) {
                        tabLinks[0].classList.add('active');
                    }
                }
                
                // Actualizar la URL con el hash correspondiente pero sin desplazamiento
                if (history.replaceState) {
                    history.replaceState(null, null, `#${tabId}`);
                }
            }

            // Posicionar alertas correctamente a la derecha
            const positionAlerts = function() {
                const alertsContainer = document.querySelector('.profile-alerts-container');
                if (alertsContainer) {
                    // Asegurar posicionamiento absoluto a la derecha
                    alertsContainer.style.position = 'fixed';
                    alertsContainer.style.top = '20px';
                    alertsContainer.style.right = '20px';
                    alertsContainer.style.zIndex = '9999';
                    alertsContainer.style.margin = '0';
                    alertsContainer.style.padding = '0';
                    alertsContainer.style.left = 'auto';
                    
                    // Asegurar que las alertas estén a la derecha
                    const alerts = alertsContainer.querySelectorAll('.custom-alert');
                    alerts.forEach(alert => {
                        alert.style.margin = '0 0 10px 0';
                        alert.style.position = 'relative';
                        alert.style.right = '0';
                    });
                }
            };
            
            // Llamar a posicionamiento inicial
            positionAlerts();
            
            // Agregar eventos de clic a los enlaces de pestañas
            tabLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const tabId = this.getAttribute('href').substring(1);
                    showTab(tabId);
                    // Evitar el desplazamiento automático al elemento con el ID
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });

            // Comprobar si existe una variable de sesión para la pestaña activa
            @if(session('active_tab'))
                showTab('{{ session('active_tab') }}');
            // Si no, mostrar la pestaña basada en el hash de la URL al cargar la página
            // o mostrar la pestaña por defecto si no hay hash
            @elseif(request()->segment(count(request()->segments())))
                const lastSegment = '{{ request()->segment(count(request()->segments())) }}';
                if (['info', 'pedidos', 'security', 'delete'].includes(lastSegment)) {
                    showTab(lastSegment);
                } else if (window.location.hash) {
                    const tabId = window.location.hash.substring(1);
                    const tabExists = document.getElementById(tabId);
                    if (tabExists) {
                        showTab(tabId);
                    } else {
                        showTab('info');
                    }
                } else {
                    showTab('info');
                }
            @else
                showTab('info');
            @endif

            // Evitar desplazamiento automático cuando hay un hash en la URL
            if (window.location.hash) {
                // Esperar un momento y luego desplazar a la parte superior
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'auto'
                    });
                }, 0);
            }

            // Animación para las tarjetas de pedidos
            const orderCards = document.querySelectorAll('.order-card');
            orderCards.forEach((card, index) => {
                card.style.opacity = "0";
                card.style.transform = "translateY(5px)";
                
                setTimeout(() => {
                    card.style.transition = "opacity 0.3s ease, transform 0.3s ease";
                    card.style.opacity = "1";
                    card.style.transform = "translateY(0)";
                }, 50 + (index * 70));
            });

            // Buscar todos los botones de toggle de contraseña
            const toggleButtons = document.querySelectorAll('.password-toggle');
            
            // Añadir el evento click a cada botón
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Encontrar el campo de contraseña asociado
                    const passwordField = this.closest('.password-container').querySelector('input');
                    
                    // Cambiar el tipo del campo entre password y text
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        this.querySelector('i').classList.remove('bi-eye');
                        this.querySelector('i').classList.add('bi-eye-slash');
                    } else {
                        passwordField.type = 'password';
                        this.querySelector('i').classList.remove('bi-eye-slash');
                        this.querySelector('i').classList.add('bi-eye');
                    }
                });
            });
            
            // Implementación del botón de confirmación para eliminar cuenta
            try {
                // Referencias a elementos del DOM
                const showDeleteModalBtn = document.getElementById('show-delete-modal');
                const deleteAccountModal = document.getElementById('delete-account-modal');
                const cancelDeleteBtn = document.getElementById('cancel-delete');
                const deleteAccountForm = document.getElementById('delete-account-form');
                const holdButton = document.getElementById('hold-to-confirm');
                const holdProgress = document.getElementById('hold-progress');
                const deletePasswordInput = document.getElementById('delete_password');
                
                if (showDeleteModalBtn && deleteAccountModal && cancelDeleteBtn && deleteAccountForm && holdButton && holdProgress) {
                    console.log('Todos los elementos están cargados correctamente');
                    
                    // Prevenir envío del formulario al presionar Enter
                    deleteAccountForm.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (deletePasswordInput.value.trim() !== '') {
                                // Si hay contraseña, mostrar el modal
                                showDeleteModalBtn.click();
                            }
                            return false;
                        }
                    });
                    
                    // Prevenir envío directo del formulario
                    deleteAccountForm.addEventListener('submit', function(e) {
                        e.preventDefault(); // Siempre prevenir envío directo
                        return false;
                    });
                    
                    // Variable para el temporizador
                    let holdTimer = null;
                    let progress = 0;
                    const holdDuration = 2000; // 2 segundos para completar
                    const updateInterval = 50; // Actualizar cada 50ms
                    let isCompleted = false; // Flag para controlar si se ha completado
                    
                    // Función para actualizar la barra de progreso
                    const updateProgress = () => {
                        if (isCompleted) return; // No hacer nada si ya está completado
                        
                        progress += (updateInterval / holdDuration) * 100;
                        
                        if (progress >= 100) {
                            // Completado
                            progress = 100;
                            holdProgress.style.width = '100%';
                            holdButton.innerHTML = '<i class="bi bi-check-circle me-2"></i>¡Confirmado!';
                            holdButton.style.color = 'white';
                            holdButton.style.backgroundColor = 'rgba(255, 112, 112, 0.6)';
                            clearInterval(holdTimer);
                            isCompleted = true; // Marcar como completado
                            
                            // Enviar el formulario después de una breve pausa
                            setTimeout(() => {
                                deleteAccountForm.submit();
                            }, 500);
                        } else {
                            // Actualizar barra de progreso
                            holdProgress.style.width = progress + '%';
                        }
                    };
                    
                    // Función para resetear el progreso
                    const resetProgress = () => {
                        if (isCompleted) return; // No reiniciar si ya está completado
                        
                        progress = 0;
                        holdProgress.style.width = '0%';
                        holdButton.innerHTML = '<i class="bi bi-hand-index-thumb me-2"></i>Mantén presionado para confirmar';
                        holdButton.style.color = '#ff7070';
                        
                        if (holdTimer) {
                            clearInterval(holdTimer);
                            holdTimer = null;
                        }
                    };
                    
                    // Evento al presionar
                    holdButton.addEventListener('mousedown', () => {
                        resetProgress();
                        holdTimer = setInterval(updateProgress, updateInterval);
                    });
                    
                    // Evento al soltar
                    holdButton.addEventListener('mouseup', resetProgress);
                    holdButton.addEventListener('mouseleave', resetProgress);
                    
                    // Eventos touch para móviles
                    holdButton.addEventListener('touchstart', (e) => {
                        e.preventDefault(); // Prevenir comportamiento por defecto
                        resetProgress();
                        holdTimer = setInterval(updateProgress, updateInterval);
                    });
                    
                    holdButton.addEventListener('touchend', resetProgress);
                    holdButton.addEventListener('touchcancel', resetProgress);
                    
                    // Mostrar el modal
                    showDeleteModalBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        deleteAccountModal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                        
                        // Resetear el estado
                        isCompleted = false;
                        progress = 0;
                        holdProgress.style.width = '0%';
                        holdButton.innerHTML = '<i class="bi bi-hand-index-thumb me-2"></i>Mantén presionado para confirmar';
                        holdButton.style.color = '#ff7070';
                        holdButton.style.backgroundColor = 'rgba(255, 112, 112, 0.1)';
                        
                        if (holdTimer) {
                            clearInterval(holdTimer);
                            holdTimer = null;
                        }
                    });
                    
                    // Cancelar y cerrar el modal
                    cancelDeleteBtn.addEventListener('click', function() {
                        deleteAccountModal.style.display = 'none';
                        document.body.style.overflow = '';
                        resetProgress();
                    });
                    
                    // Cerrar el modal haciendo clic fuera
                    deleteAccountModal.addEventListener('click', function(e) {
                        if (e.target === deleteAccountModal) {
                            deleteAccountModal.style.display = 'none';
                            document.body.style.overflow = '';
                            resetProgress();
                        }
                    });
                    
                    console.log('Inicialización del botón de confirmar completada');
                } else {
                    console.error('No se pudieron encontrar todos los elementos necesarios');
                }
            } catch (error) {
                console.error('Error al inicializar el modal de confirmación:', error);
            }
            
            // Si hay un mensaje de éxito, asegurarse de que aparezca como alerta flotante
            if (document.querySelector('.profile-alert')) {
                // La alerta ya está presente en el DOM
                // Posicionar adecuadamente
                positionAlerts();
            } else if (window.customAlerts && document.getElementById('security').classList.contains('active')) {
                // Si el tab de seguridad está activo y hay un mensaje de éxito en la sesión
                const successMessage = document.querySelector('meta[name="session-success"]');
                if (successMessage) {
                    window.customAlerts.show('success', successMessage.content);
                    // Dar tiempo para que se cree el alerta y luego posicionarla
                    setTimeout(positionAlerts, 100);
                }
            }

            // Verificar si hay errores en la validación del formulario de contraseña
            @if($errors->any() && old('active_tab') == 'security')
                showTab('security');
            @endif

            // Implementación del modal para cancelar pedido
            try {
                // Referencias a elementos del DOM para el modal de cancelar pedido
                const cancelOrderModal = document.getElementById('cancel-order-modal');
                const confirmCancelBtn = document.getElementById('confirm-cancel');
                const closeCancelModalBtn = document.getElementById('close-cancel-modal');
                const cancelBtns = document.querySelectorAll('.show-cancel-modal');
                
                let currentCancelForm = null;
                
                if (cancelOrderModal && confirmCancelBtn && closeCancelModalBtn && cancelBtns.length > 0) {
                    console.log('Elementos del modal de cancelación cargados correctamente');
                    
                    // Mostrar el modal al hacer clic en el botón cancelar
                    cancelBtns.forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const orderId = this.getAttribute('data-id');
                            currentCancelForm = document.getElementById('cancel-form-' + orderId);
                            
                            if (currentCancelForm) {
                                cancelOrderModal.style.display = 'flex';
                                document.body.style.overflow = 'hidden';
                            } else {
                                console.error('No se encontró el formulario de cancelación');
                            }
                        });
                    });
                    
                    // Cerrar el modal
                    closeCancelModalBtn.addEventListener('click', function() {
                        cancelOrderModal.style.display = 'none';
                        document.body.style.overflow = '';
                        currentCancelForm = null;
                    });
                    
                    // Confirmar cancelación
                    confirmCancelBtn.addEventListener('click', function() {
                        if (currentCancelForm) {
                            currentCancelForm.submit();
                        }
                    });
                    
                    // Cerrar el modal haciendo clic fuera
                    cancelOrderModal.addEventListener('click', function(e) {
                        if (e.target === cancelOrderModal) {
                            cancelOrderModal.style.display = 'none';
                            document.body.style.overflow = '';
                            currentCancelForm = null;
                        }
                    });
                    
                } else {
                    console.error('No se pudieron encontrar todos los elementos necesarios para el modal de cancelación');
                }
            } catch (error) {
                console.error('Error al inicializar el modal de cancelación:', error);
            }

            // Manejo de la alerta informativa para pedidos
            try {
                const pedidosTab = document.getElementById('pedidos-tab');
                const infoAlert = document.getElementById('pedidos-info-alert');
                const closeInfoBtn = document.getElementById('close-info-alert');
                
                // Verificar si la alerta debe mostrarse
                const shouldShowAlert = () => {
                    // Si el usuario está en la pestaña de pedidos y no ha cerrado la alerta en esta sesión
                    return !localStorage.getItem('infoAlertClosed');
                };
                
                // Función para mostrar la alerta si es necesario
                const checkAndShowAlert = () => {
                    if (infoAlert && pedidosTab.classList.contains('active') && shouldShowAlert()) {
                        infoAlert.style.display = 'block';
                    }
                };
                
                // Cuando se haga clic en el botón de cerrar
                if (closeInfoBtn) {
                    closeInfoBtn.addEventListener('click', function() {
                        // Ocultar la alerta
                        if (infoAlert) {
                            infoAlert.style.display = 'none';
                        }
                        
                        // Guardar en localStorage que la alerta se ha cerrado
                        localStorage.setItem('infoAlertClosed', 'true');
                    });
                }
                
                // Cuando se hace clic en la pestaña de pedidos
                if (pedidosTab) {
                    pedidosTab.addEventListener('click', function() {
                        checkAndShowAlert();
                    });
                }
                
                // Comprobar al cargar la página
                if (window.location.hash === '#pedidos' || document.getElementById('pedidos').classList.contains('active')) {
                    checkAndShowAlert();
                }
                
            } catch (error) {
                console.error('Error al manejar la alerta informativa:', error);
            }
        });
    </script>
@endsection

<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', __('app.site_title'))</title>
    <meta name="description" content="{{ __('app.site_description') }}">
    <meta name="keywords" content="pasteles, repostería, tartas, dulces, postres, personalizado, eventos, cumpleaños">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta tags para mensajes de sesión -->
    @if(session('success'))
    <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
    <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if(session('warning'))
    <meta name="session-warning" content="{{ session('warning') }}">
    @endif
    @if(session('info'))
    <meta name="session-info" content="{{ session('info') }}">
    @endif

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alerta-compra.css') }}" rel="stylesheet">
    <link href="{{ asset('css/compra.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-alerts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body data-env="{{ config('app.env') }}" class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('index') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename" style="font-family: 'Dancing Script', cursive; font-size: 2rem;">Mi sueño
                    <span style="font-size: 2rem; margin-right: 5px;"> dulce</span>
                </h1>
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="logo">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul class="nav-links">
                    <li><a href="{{ route('index') }}#hero" class="{{ request()->routeIs('index') && request()->has('hero') ? 'active' : '' }}">{{ __('app.nav.home') }}</a></li>
                    <li><a href="{{ route('index') }}#about" class="{{ request()->routeIs('index') && request()->has('about') ? 'active' : '' }}">{{ __('app.nav.about') }}</a></li>
                    <li><a href="{{ route('index') }}#menu" class="{{ request()->routeIs('index') && request()->has('menu') ? 'active' : '' }}">{{ __('app.nav.menu') }}</a></li>
                    <li><a href="{{ route('index') }}#proceso" class="{{ request()->routeIs('index') && request()->has('proceso') ? 'active' : '' }}">{{ __('app.nav.process') }}</a></li>
                    <li><a href="{{ route('index') }}#contact" class="{{ request()->routeIs('index') && request()->has('contact') ? 'active' : '' }}">{{ __('app.nav.contact') }}</a></li>
                    @auth
                        <li class="d-xl-none"><a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil') ? 'active' : '' }}">{{ __('app.nav.profile') }}</a></li>
                        @if(auth()->user()->is_admin == 1)
                            <li class="d-xl-none"><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('app.nav.admin_panel') }}</a></li>
                        @endif
                        <li class="d-xl-none">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">{{ __('app.nav.logout') }}</button>
                            </form>
                        </li>
                    @else
                        <li class="d-xl-none">
                            <a href="{{ route('login') }}" class="login-link">{{ __('app.nav.login') }}</a>
                        </li>
                    @endauth
                    <li class="mobile-only d-xl-none">
                        <a href="{{ route('carrito.index') }}" class="sweet-cart-btn">
                            <div class="cart-container">
                                <div class="cart-icon-wrapper">
                                    <i class="bi bi-cart3"></i>
                                    <div class="cart-badge">
                                        <span class="cart-count">0</span>
                                    </div>
                                </div>
                                <div class="cart-text">{{ __('app.nav.cart') }}</div>
                            </div>
                        </a>
                    </li>
                    <!-- Selector de idiomas para móvil -->
                    <li class="language-selector-mobile d-xl-none">
                        <div class="language-selector">
                            <a href="{{ route('language.switch', 'es') }}"
                               class="lang-link {{ app()->getLocale() == 'es' ? 'active' : '' }}"
                               title="{{ __('app.change_language') }}">
                                <i class="bi bi-translate me-2"></i>ES
                            </a>
                            <span class="language-divider">|</span>
                            <a href="{{ route('language.switch', 'en') }}"
                               class="lang-link {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                               title="{{ __('app.change_language') }}">
                                EN
                            </a>
                        </div>
                    </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="d-none d-xl-flex align-items-center">
                <!-- Selector de idiomas para desktop -->
                <div class="language-selector me-3">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="lang-link {{ app()->getLocale() == 'es' ? 'active' : '' }}"
                       title="{{ __('app.change_language') }}">
                        ES
                    </a>
                    <span class="language-divider">|</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="lang-link {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                       title="{{ __('app.change_language') }}">
                        EN
                    </a>
                </div>

                @auth
                    <div class="dropdown me-3">
                        <button class="user-profile-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-icon-wrapper">
                                <i class="bi bi-person-circle" style="margin-bottom: -2.2px;"></i>
                                <span class="profile-text">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu profile-dropdown" aria-labelledby="userDropdown" style="padding-top: 0px; padding-bottom: 0px;">
                            <li class="dropdown-user-info" style="border-bottom-width: 0px;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="dropdown-user-name">{{ auth()->user()->name }} {{ auth()->user()->apellido }}</div>
                                        <div class="dropdown-user-email">{{ auth()->user()->email }}</div>
                                    </div>
                                    <button type="button" class="btn-close-dropdown" aria-label="Close">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider" style="margin-top: 0px; margin-bottom: 0px;"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('perfil') }}">
                                    <i class="bi bi-person me-2"></i>{{ __('app.nav.profile') }}
                                </a>
                            </li>
                            @if(auth()->user()->is_admin == 1)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>{{ __('app.nav.admin_panel') }}
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider" style="margin-top: 0px; margin-bottom: 0px;"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('app.nav.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('carrito.index') }}" class="sweet-cart-btn">
                        <div class="cart-container">
                            <div class="cart-icon-wrapper">
                                <i class="bi bi-cart3"></i>
                                <div class="cart-badge">
                                    <span class="cart-count">0</span>
                                </div>
                            </div>
                            <div class="cart-text">Mi carrito</div>
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-proceso">{{ __('app.nav.login') }}</a>
                    <a href="{{ route('carrito.index') }}" class="sweet-cart-btn">
                        <div class="cart-container">
                            <div class="cart-icon-wrapper">
                                <i class="bi bi-cart3"></i>
                                <div class="cart-badge">
                                    <span class="cart-count">0</span>
                                </div>
                            </div>
                            <div class="cart-text">{{ __('app.nav.cart') }}</div>
                        </div>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Botón para cambiar tema -->
    <button class="theme-toggle-btn" id="themeToggle" title="{{ __('app.theme.toggle') }}">
        <i class="bi bi-sun-fill"></i>
    </button>

    <main class="main">
        {{-- Alertas - eliminada la restricción de sección de contenido --}}
        @if(!Request::is('perfil*'))
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('Mensaje de éxito:', '{{ session('success') }}');
                        if(window.customAlerts) {
                            window.customAlerts.show('success', '{{ session('success') }}');
                        }
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('Mensaje de error:', '{{ session('error') }}');
                        if(window.customAlerts) {
                            window.customAlerts.show('danger', '{{ session('error') }}');
                        }
                    });
                </script>
            @endif

            @if (session('warning'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('Mensaje de advertencia:', '{{ session('warning') }}');
                        if(window.customAlerts) {
                            window.customAlerts.show('warning', '{{ session('warning') }}');
                        }
                    });
                </script>
            @endif

            @if (session('info'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('Mensaje de info:', '{{ session('info') }}');
                        if(window.customAlerts) {
                            window.customAlerts.show('info', '{{ session('info') }}');
                        }
                    });
                </script>
            @endif
        @endif

        @yield('content')
    </main>

    {{-- Ocultar footer en la página de perfil --}}
    @if(!Request::is('perfil*'))
        @include('layouts.footer')
    @endif

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>

    <!-- Main JS Files -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/custom-alerts.js') }}"></script>
    <script src="{{ asset('js/custom-alerts-fix.js') }}"></script>
    <script src="{{ asset('js/alerta-compra.js') }}"></script>
    <script src="{{ asset('js/productos.js') }}"></script>
    <script src="{{ asset('js/carrito.js') }}"></script>
    <script src="{{ asset('js/contact-form.js') }}"></script>

    <script>
        // Script para cerrar el menú desplegable con el botón X
        document.addEventListener('DOMContentLoaded', function() {
            const closeDropdownBtn = document.querySelector('.btn-close-dropdown');
            if (closeDropdownBtn) {
                closeDropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dropdown = document.getElementById('userDropdown');
                    if (dropdown) {
                        bootstrap.Dropdown.getInstance(dropdown).hide();
                    }
                });
            }

            // Corregir problemas de animación en el dropdown
            const userProfileBtn = document.getElementById('userDropdown');
            if (userProfileBtn) {
                userProfileBtn.addEventListener('shown.bs.dropdown', function() {
                    document.querySelector('.profile-icon-wrapper i').style.opacity = '0';
                    document.querySelector('.profile-text').style.opacity = '1';
                });

                userProfileBtn.addEventListener('hidden.bs.dropdown', function() {
                    if (!userProfileBtn.matches(':hover')) {
                        document.querySelector('.profile-icon-wrapper i').style.opacity = '0.9';
                        document.querySelector('.profile-text').style.opacity = '0';
                    }
                });
            }
        });
    </script>

    <style>
        .login-btn:hover, .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 112, 112, 0.4) !important;
        }

        .login-btn:active, .register-btn:active {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(255, 112, 112, 0.3) !important;
        }

        /* Estilos del menú de perfil de usuario */
        .user-profile-btn {
            position: relative;
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
            transition: none; /* Eliminamos la transición global */
        }

        .profile-icon-wrapper {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 112, 112, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ff7070;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .profile-icon-wrapper i {
            font-size: 1.5rem;
            color: #ff7070;
            transition: all 0.3s ease;
            position: absolute;
            opacity: 0.9;
        }

        .profile-text {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ff7070;
            position: absolute;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }

        /* Aplicamos los efectos solo en hover, no cuando está abierto */
        .user-profile-btn:hover .profile-icon-wrapper {
            transform: scale(1.1);
            background-color: rgba(255, 112, 112, 0.2);
            box-shadow: 0 4px 8px rgba(255, 112, 112, 0.3);
        }

        .user-profile-btn:hover .profile-icon-wrapper i {
            opacity: 0;
            transform: scale(0.5);
        }

        .user-profile-btn:hover .profile-text {
            opacity: 1;
            transform: scale(1);
        }

        /* Estilos para el estado abierto del dropdown */
        .user-profile-btn.show .profile-icon-wrapper {
            transform: scale(1.1);
            background-color: rgba(255, 112, 112, 0.2);
            box-shadow: 0 4px 8px rgba(255, 112, 112, 0.3);
        }

        /* Evitar la transición al cerrar el dropdown */
        .dropdown-menu.profile-dropdown {
            animation: none;
            transition: none;
            transform: none !important;
            position: absolute;
            top: 100% !important;
            margin-top: 0.5rem;
            min-width: 240px;
            padding: 0.5rem 0;
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            opacity: 0;
            display: block;
            pointer-events: none;
        }

        .dropdown-menu.show.profile-dropdown {
            animation: none;
            opacity: 1;
            pointer-events: auto;
        }

        @keyframes dropdown-fade {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .dropdown-user-info {
            padding: 1rem;
            background-color: #fff4e6;
            border-bottom: 1px solid rgba(255, 112, 112, 0.1);
        }

        .dropdown-user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .dropdown-user-email {
            font-size: 0.8rem;
            color: #777;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: #555;
            transition: color 0.2s ease;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 112, 112, 0.08);
            color: #ff7070;
        }

        .dropdown-item i {
            font-size: 1rem;
            color: #ff7070;
            width: 20px;
            text-align: center;
        }

        /* Botón de cierre del menú desplegable */
        .btn-close-dropdown {
            background: transparent;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
            padding: 0;
        }

        .btn-close-dropdown i {
            font-size: 1.2rem;
            color: #999;
            transition: color 0.2s ease;
        }

        .btn-close-dropdown:hover {
            background-color: rgba(0,0,0,0.05);
        }

        .btn-close-dropdown:hover i {
            color: #ff7070;
        }

        /* Estilo específico para el botón de cerrar sesión */
        .logout-item {
            transition: color 0.2s ease !important;
            transform: none !important;
            box-shadow: none !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            padding: 0.5rem 1rem !important;
            text-align: left !important;
            color: #555 !important;
            background-color: transparent !important;
            border: none !important;
            width: 100% !important;
            cursor: pointer !important;
        }

        .logout-item:hover {
            background-color: rgba(255, 112, 112, 0.08) !important;
            color: #ff7070 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Nuevo diseño del carrito sin fondo */
        .sweet-cart-btn {
            position: relative;
            text-decoration: none;
            margin-left: 10px;
            display: inline-block;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .cart-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            transition: all 0.4s ease;
        }

        .cart-icon-wrapper {
            position: relative;
            margin-right: 8px;
        }

        .cart-icon-wrapper i {
            font-size: 2rem;
            color: #ff7070;
            filter: none;
            transition: all 0.3s ease;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -12px;
            background: white;
            color: #ff7070;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: none;
            border: 2px solid #ff7070;
            transition: all 0.3s ease;
        }

        .cart-text {
            color: #ff7070;
            font-family: 'Dancing Script', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            text-shadow: none;
            transition: all 0.3s ease;
        }

        .sweet-cart-btn:hover {
            transform: translateY(-3px);
        }

        .sweet-cart-btn:hover .cart-container {
            box-shadow: none;
        }

        .sweet-cart-btn:hover .cart-icon-wrapper i {
            color: #ff5050;
            transform: translateY(-2px) scale(1.1);
        }

        .sweet-cart-btn:hover .cart-badge {
            transform: scale(1.2);
            background: #fff4e5;
        }

        .sweet-cart-btn:hover .cart-text {
            color: #ff5050;
            transform: scale(1.05);
        }

        .sweet-cart-btn:active {
            transform: translateY(-1px);
        }

        .sweet-cart-btn:active .cart-icon-wrapper i {
            transform: translateY(0) scale(1.05);
        }

        .language-selector {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lang-link {
            padding: 6px 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 4px;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lang-link:hover {
            color: #ff7070;
            background-color: rgba(255, 112, 112, 0.1);
        }

        .lang-link.active {
            color: #ff7070;
            background-color: rgba(255, 112, 112, 0.15);
            font-weight: 700;
        }

        .language-divider {
            color: #ccc;
            font-weight: 300;
            user-select: none;
        }

        /* Estilos para móvil */
        .language-selector-mobile {
            border-top: 1px solid rgba(255, 112, 112, 0.1);
            margin-top: 20px;
            padding-top: 20px;
        }

        .language-selector-mobile .language-selector {
            justify-content: center;
            padding: 10px 0;
        }

        .language-selector-mobile .lang-link {
            padding: 10px 15px;
            font-size: 1rem;
        }

        /* Tema oscuro */
        [data-theme="dark"] .lang-link {
            color: #ccc;
        }

        [data-theme="dark"] .lang-link:hover {
            color: #ff7070;
            background-color: rgba(255, 112, 112, 0.1);
        }

        [data-theme="dark"] .lang-link.active {
            color: #ff7070;
            background-color: rgba(255, 112, 112, 0.15);
        }

        [data-theme="dark"] .language-divider {
            color: #666;
        }

        @media (max-width: 1199px) {
            .language-selector {
                margin-left: 0;
                justify-content: center;
                padding: 10px 0;
            }
        }
    </style>

    <!-- Script para control de tema oscuro/claro -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            const themeIcon = themeToggleBtn.querySelector('i');

            // Control del menú móvil
            const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
            const navMenu = document.querySelector('.navmenu');
            const body = document.body;

            if (mobileNavToggle && navMenu) {
                mobileNavToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('show');
                    body.style.overflow = navMenu.classList.contains('show') ? 'hidden' : '';

                    // Cambiar el ícono del botón
                    this.classList.toggle('bi-list');
                    this.classList.toggle('bi-x');
                });

                // Cerrar menú al hacer clic en un enlace
                const navLinks = navMenu.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        navMenu.classList.remove('show');
                        body.style.overflow = '';
                        mobileNavToggle.classList.add('bi-list');
                        mobileNavToggle.classList.remove('bi-x');
                    });
                });

                // Cerrar menú al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (!navMenu.contains(e.target) && !mobileNavToggle.contains(e.target) && navMenu.classList.contains('show')) {
                        navMenu.classList.remove('show');
                        body.style.overflow = '';
                        mobileNavToggle.classList.add('bi-list');
                        mobileNavToggle.classList.remove('bi-x');
                    }
                });
            }

            // Resto del código existente del tema
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            themeToggleBtn.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });

            function applyTheme(theme) {
                htmlElement.setAttribute('data-theme', theme);
                if (theme === 'dark') {
                    themeIcon.classList.remove('bi-sun-fill');
                    themeIcon.classList.add('bi-moon-fill');
                    themeToggleBtn.title = "{{ __('app.theme.light') }}";
                    document.querySelector('link[rel="icon"]').href = "{{ asset('img/logo/img-ico.ico') }}";
                } else {
                    themeIcon.classList.remove('bi-moon-fill');
                    themeIcon.classList.add('bi-sun-fill');
                    themeToggleBtn.title = "{{ __('app.theme.dark') }}";
                    document.querySelector('link[rel="icon"]').href = "{{ asset('img/favicon.png') }}";
                }
            }
        });
    </script>

</body>

</html>

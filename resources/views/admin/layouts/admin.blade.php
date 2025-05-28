<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración') - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7070;
            --primary-color-rgb: 255, 112, 112; /* Versión RGB del color primario */
            --accent-color: #ff7070;
            --bg-color: #fff4e5;
            --text-color: #4a4a4a;
            --heading-color: #37373f;
            --surface-color: #ffffff;
            --border-color: #ebebeb;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--surface-color);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease, width 0.3s ease, box-shadow 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar .sitename {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: var(--primary-color);
            margin: 0;
        }

        .sidebar-body {
            padding: 20px 0;
            overflow-y: auto;
            height: calc(100vh - 150px); /* Altura ajustable basada en header y footer */
        }

        .nav-link {
            padding: 12px 20px;
            color: var(--text-color);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative; /* Para los indicadores */
        }

        .nav-link:hover {
            background-color: rgba(255, 112, 112, 0.1);
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: rgba(255, 112, 112, 0.15);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--primary-color);
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 24px; /* Ancho fijo para mejorar alineación */
            text-align: center;
        }

        .content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            color: var(--heading-color);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card {
            background-color: var(--surface-color);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 20px;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            background-color: var(--surface-color);
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .sidebar-footer a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-footer a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar-footer i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .sidebar-footer a:hover i {
            transform: translateX(-2px);
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }
            .content {
                margin-left: 0;
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
                box-shadow: 2px 0 15px rgba(0,0,0,0.15);
            }
            body.sidebar-open .content {
                margin-left: 0; /* No movemos el contenido en móvil */
            }
            body.sidebar-open::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                animation: fadeIn 0.3s ease forwards;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .toggle-sidebar {
                display: flex !important;
            }
        }

        .toggle-sidebar {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1010;
            background-color: var(--surface-color);
            color: var(--primary-color);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 112, 112, 0.2);
            transition: all 0.3s ease;
        }
        
        .toggle-sidebar:hover {
            background-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        /* Estilos para los botones de tema */
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px; /* Cambiado de left a right para evitar solapamiento con sidebar */
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--surface-color);
            color: var(--primary-color);
            border: 1px solid rgba(255, 112, 112, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .theme-toggle-btn:hover {
            transform: scale(1.1) rotate(15deg);
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 112, 112, 0.3);
        }
        
        .theme-toggle-btn i {
            font-size: 1.3rem;
        }

        /* Estilos específicos para Usuarios */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .admin-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .admin-action-btn {
            padding: 5px;
            color: var(--text-color);
            transition: all 0.2s ease;
        }

        .admin-action-btn:hover {
            color: var(--primary-color);
        }

        .admin-action-btn.edit:hover {
            color: #4dafff;
        }

        .admin-action-btn.delete:hover {
            color: #dc3545;
        }
        
        .admin-action-btn.view:hover {
            color: #4dafff;
        }

        .delete-form {
            display: inline;
        }

        /* Estilos específicos para Productos */
        .admin-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .search-container {
            max-width: 300px;
            position: relative;
        }

        .search-container .form-control {
            padding-left: 40px;
            border-radius: 30px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 9px;
            color: #aaa;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-tabs .nav-link.active {
            color: #198754;
            font-weight: 600;
        }

        .admin-toolbar {
            margin-bottom: 30px;
            padding: 15px;
            background-color: var(--surface-color);
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilos específicos para Pedidos */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pagado {
            background-color: rgba(50, 194, 125, 0.15);
            color: #32c27d;
        }

        .status-pendiente {
            background-color: rgba(255, 170, 0, 0.15);
            color: #ffaa00;
        }

        .status-enviado, .status-confirmado {
            background-color: rgba(77, 175, 255, 0.15);
            color: #4dafff;
        }

        .status-terminado {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-recogido {
            background-color: rgba(111, 66, 193, 0.15);
            color: #6f42c1;
        }

        .status-cancelado {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        
        .status-en_proceso {
            background-color: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }

        .order-total {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-id {
            font-weight: 600;
            color: var(--heading-color);
        }

        .filters-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 15px;
            border-radius: 20px;
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Estilos para tablas */
        .table {
            color: var(--text-color);
        }

        .table thead th {
            border-bottom: 2px solid var(--border-color);
            color: var(--heading-color);
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 112, 112, 0.05);
        }

        /* Estilos para botones */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #e56464;
            border-color: #e56464;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            color: white;
        }

        .btn {
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Toggle Sidebar Button (visible on mobile) -->
    <div class="toggle-sidebar" aria-label="Abrir menú lateral">
        <i class="bi bi-list"></i>
    </div>

    <!-- Botón para cambiar tema -->
    <button class="theme-toggle-btn" id="themeToggle" title="Cambiar modo">
        <i class="bi bi-sun-fill"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sitename">Mi sueño dulce</h1>
            <div>Panel de Administración</div>
        </div>
        <div class="sidebar-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.productos*') ? 'active' : '' }}" href="{{ route('admin.productos') }}">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}" href="{{ route('admin.usuarios') }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}" href="{{ route('admin.pedidos') }}">
                        <i class="bi bi-bag"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.resenas*') ? 'active' : '' }}" href="{{ route('admin.resenas') }}">
                        <i class="bi bi-star"></i> Reseñas
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <a href="{{ route('index') }}">
                <i class="bi bi-arrow-left"></i> Volver a la tienda
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestión del sidebar
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const mainContent = document.querySelector('.content');
            
            // Detectar si es un dispositivo móvil
            const isMobile = () => window.innerWidth < 992;
            
            // Función para abrir/cerrar el sidebar
            const toggleSidebar = () => {
                document.body.classList.toggle('sidebar-open');
                
                // Añadir clase para accesibilidad
                if (document.body.classList.contains('sidebar-open')) {
                    sidebar.setAttribute('aria-expanded', 'true');
                    toggleBtn.setAttribute('aria-label', 'Cerrar menú lateral');
                } else {
                    sidebar.setAttribute('aria-expanded', 'false');
                    toggleBtn.setAttribute('aria-label', 'Abrir menú lateral');
                }
            };
            
            // Inicializar estado del sidebar
            if (sidebar) {
                sidebar.setAttribute('aria-expanded', isMobile() ? 'false' : 'true');
            }
            
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-label', 'Abrir menú lateral');
                
                // Evento de clic para el botón de toggle
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            // Cerrar el sidebar al hacer clic en el contenido principal (en móvil)
            if (mainContent) {
                mainContent.addEventListener('click', function() {
                    if (isMobile() && document.body.classList.contains('sidebar-open')) {
                        toggleSidebar();
                    }
                });
            }
            
            // Cerrar sidebar al cambiar tamaño de ventana a escritorio
            window.addEventListener('resize', function() {
                if (!isMobile() && document.body.classList.contains('sidebar-open')) {
                    document.body.classList.remove('sidebar-open');
                }
            });
            
            // Cerrar el sidebar al hacer clic en un enlace del menú (en móvil)
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (isMobile() && document.body.classList.contains('sidebar-open')) {
                        toggleSidebar();
                    }
                });
            });
            
            // Cerrar el sidebar al hacer clic fuera de él
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggleBtn = toggleBtn.contains(event.target);
                
                if (isMobile() && 
                    document.body.classList.contains('sidebar-open') && 
                    !isClickInsideSidebar && 
                    !isClickOnToggleBtn) {
                    toggleSidebar();
                }
            });

            // Tema oscuro/claro
            const themeToggleBtn = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            
            if (themeToggleBtn) {
                const themeIcon = themeToggleBtn.querySelector('i');
                
                // Comprobar si hay una preferencia guardada
                const savedTheme = localStorage.getItem('theme') || 'light';
                
                // Aplicar el tema guardado
                applyTheme(savedTheme);
                
                // Manejar clic en botón de cambio de tema
                themeToggleBtn.addEventListener('click', function() {
                    const currentTheme = htmlElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    applyTheme(newTheme);
                    
                    // Guardar preferencia
                    localStorage.setItem('theme', newTheme);
                });
                
                // Función para aplicar el tema
                function applyTheme(theme) {
                    htmlElement.setAttribute('data-theme', theme);
                    
                    // Actualizar ícono del botón
                    if (theme === 'dark') {
                        themeIcon.classList.remove('bi-sun-fill');
                        themeIcon.classList.add('bi-moon-fill');
                        themeToggleBtn.title = "Cambiar a modo claro";
                    } else {
                        themeIcon.classList.remove('bi-moon-fill');
                        themeIcon.classList.add('bi-sun-fill');
                        themeToggleBtn.title = "Cambiar a modo oscuro";
                    }
                    
                    // Actualizar colores de elementos dinámicos
                    updateDynamicElements(theme);
                }
                
                // Actualizar elementos dinámicos según el tema
                function updateDynamicElements(theme) {
                    // Ajustar sidebar para evitar parpadeo
                    if (sidebar) {
                        if (theme === 'dark') {
                            sidebar.style.backgroundColor = '#1c1c1c';
                            sidebar.style.borderRight = '1px solid #333';
                        } else {
                            sidebar.style.backgroundColor = '';
                            sidebar.style.borderRight = '';
                        }
                    }
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html> 
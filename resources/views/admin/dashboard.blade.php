<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7070;
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

        .stat-card {
            background-color: var(--surface-color);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: white;
        }

        .stat-icon.products {
            background-color: #ff7070;
        }

        .stat-icon.users {
            background-color: #4dafff;
        }

        .stat-icon.orders {
            background-color: #32c27d;
        }

        .stat-icon.income {
            background-color: #ffaa00;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--heading-color);
        }

        .stat-label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
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

        .recent-order {
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
        }

        .recent-order:last-child {
            border-bottom: none;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary-color);
        }

        .order-date {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .order-total {
            font-weight: 600;
            color: var(--heading-color);
        }

        .order-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .order-status.completed {
            background-color: rgba(50, 194, 125, 0.15);
            color: #32c27d;
        }

        .order-status.processing {
            background-color: rgba(255, 170, 0, 0.15);
            color: #ffaa00;
        }

        .order-status.pending {
            background-color: rgba(77, 175, 255, 0.15);
            color: #4dafff;
        }

        .welcome-banner {
            background-color: #ff7070;
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-banner p {
            opacity: 0.9;
            max-width: 600px;
        }

        .welcome-banner .time {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .welcome-banner .pattern {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 35%;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIG9wYWNpdHk9IjAuMiI+CjxwYXRoIGQ9Ik0wIDEwMEwxMDAgMEwxMDAgMTAwSDBaIiBmaWxsPSJ3aGl0ZSIvPgo8L2c+Cjwvc3ZnPg==');
            background-size: 30px 30px;
            z-index: 1;
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
    </style>
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
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.productos') }}">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.usuarios') }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.pedidos') }}">
                        <i class="bi bi-bag"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.resenas') }}">
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
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="pattern"></div>
            <div class="time">{{ date('l, d F Y') }}</div>
            <h2>Bienvenido, {{ Auth::check() ? Auth::user()->name : 'Administrador' }}</h2>
            <p>Accede a todas las herramientas para administrar tu tienda desde este panel. Gestiona tus productos, usuarios y pedidos.</p>
        </div>

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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon products">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-value">{{ $totalProductos }}</div>
                    <div class="stat-label">Productos</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-value">{{ $totalUsuarios }}</div>
                    <div class="stat-label">Usuarios</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div class="stat-value">{{ $totalCompras }}</div>
                    <div class="stat-label">Pedidos</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon income">
                        <i class="bi bi-currency-euro"></i>
                    </div>
                    <div class="stat-value">
                        <?php
                            $totalVentas = 0;
                            foreach ($ventasRecientes as $venta) {
                                $totalVentas += $venta->total;
                            }
                            echo number_format($totalVentas, 2);
                        ?>
                    </div>
                    <div class="stat-label">Ventas Recientes (€)</div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Pedidos Recientes
                    </div>
                    <div class="card-body">
                        @if(count($ventasRecientes) > 0)
                            @foreach($ventasRecientes as $venta)
                                <div class="recent-order">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="order-id">#{{ $venta->id }}</div>
                                            <div class="order-date">{{ $venta->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Cliente:</strong> {{ $venta->user ? $venta->user->name : 'Usuario eliminado' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Artículos:</strong> {{ $venta->detalles ? $venta->detalles->sum('cantidad') : '0' }}
                                        </div>
                                        <div class="col-md-2">
                                            <div class="order-total">€{{ number_format($venta->total ?? 0, 2) }}</div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <span class="order-status {{ strtolower($venta->estado ?? 'pendiente') }}">{{ $venta->estado ?? 'Pendiente' }}</span>
                                            <a href="{{ route('admin.pedidos.ver', $venta->id) }}" class="btn btn-sm btn-link">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-3 text-end">
                                <a href="{{ route('admin.pedidos') }}" class="btn btn-sm btn-outline-secondary">
                                    Ver todos los pedidos <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center p-4">
                                <i class="bi bi-basket" style="font-size: 3rem; color: #e8e8e8;"></i>
                                <p class="mt-3">No hay pedidos recientes.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

            // Format date in welcome banner
            const formatDate = () => {
                const date = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formatter = new Intl.DateTimeFormat('es-ES', options);
                const parts = formatter.formatToParts(date);

                let formattedDate = '';
                parts.forEach(part => {
                    if (part.type === 'weekday') {
                        formattedDate += part.value.charAt(0).toUpperCase() + part.value.slice(1);
                    } else if (part.type === 'month') {
                        formattedDate += part.value.charAt(0).toUpperCase() + part.value.slice(1);
                    } else {
                        formattedDate += part.value;
                    }
                });

                const timeElement = document.querySelector('.time');
                if (timeElement) {
                    timeElement.textContent = formattedDate;
                }
            };

            formatDate();

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
            
            // Agregar efecto de hover a las tarjetas estadísticas
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>

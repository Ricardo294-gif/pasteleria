<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $pedido->codigo ?? $pedido->id }} - Mi Sueño Dulce</title>
    
    <!-- Favicon -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" id="favicon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7070;
            --accent-color: #ff7070;
            --bg-color: #fff9f9;
            --text-color: #4a4a4a;
            --heading-color: #37373f;
            --surface-color: #ffffff;
            --border-color: #f0f0f0;
            --shadow: 0 2px 10px rgba(0,0,0,0.05);
            --radius: 10px;
        }

        [data-theme="dark"] {
            --primary-color: #ff9494;
            --accent-color: #ff9494;
            --bg-color: #1a1a1a;
            --text-color: #e0e0e0;
            --heading-color: #ffffff;
            --surface-color: #2d2d2d;
            --border-color: #444;
            --shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 14px;
            line-height: 1.5;
        }

        .logo-container {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        /* Estilos para el logo */
        .sitename {
            color: #000000 !important;
        }

        .sitename span {
            color: #ff7070 !important;
        }

        /* Estilos para el logo en modo oscuro */
        [data-theme="dark"] .sitename {
            color: #ffffff !important;
        }

        [data-theme="dark"] .sitename span {
            color: #ff9494 !important;
        }

        .site-logo {
            height: 45px;
            width: auto;
        }

        .site-logo-link {
            text-decoration: none;
        }

        .content-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 500;
            margin-bottom: 1rem;
            color: var(--heading-color);
        }

        .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 20px;
            background-color: var(--surface-color);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1rem;
            color: var(--heading-color);
        }

        .card-body {
            padding: 20px;
        }

        .footer {
            margin-top: auto;
            background-color: #37373f;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 12px;
        }

        /* Estilos para la barra de progreso - Versión minimalista */
        .order-progress-tracker {
            position: relative;
            margin: 40px 0;
            padding: 0 10px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .progress-bg {
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--border-color);
            z-index: 0;
        }

        .progress-fill {
            position: absolute;
            top: 15px;
            left: 0;
            height: 2px;
            background-color: var(--primary-color);
            z-index: 0;
            transition: width 0.5s ease;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 100px;
            position: relative;
        }

        .step-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #fff;
            margin-bottom: 8px;
            color: var(--text-color);
            font-size: 14px;
            position: relative;
            transition: all 0.3s ease;
            border: 2px solid var(--border-color);
        }

        .step-label {
            font-weight: 500;
            font-size: 12px;
            color: var(--text-color);
            margin-bottom: 2px;
            text-align: center;
        }

        .step-desc {
            font-size: 10px;
            color: #999;
            text-align: center;
            max-width: 80px;
        }

        /* Estados activos e inactivos */
        .progress-step.active .step-icon {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .progress-step.active .step-label {
            color: var(--heading-color);
            font-weight: 600;
        }

        .progress-step.current .step-icon {
            transform: scale(1.1);
            box-shadow: 0 0 0 3px rgba(255, 112, 112, 0.2);
        }

        /* Cancelado - estado especial */
        .progress-step.cancelado .step-icon {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Estado actual del pedido */
        .current-status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 15px;
            background-color: rgba(255, 112, 112, 0.1);
            color: var(--primary-color);
        }

        .status-pagado {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .status-confirmado {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-en_proceso {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .status-terminado {
            background-color: rgba(253, 126, 20, 0.1);
            color: #fd7e14;
        }

        .status-recogido {
            background-color: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }

        .status-cancelado {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Diseño de productos */
        .product-item {
            padding: 15px;
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .product-item:hover {
            box-shadow: var(--shadow);
        }

        .product-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 12px;
            color: #777;
        }

        .product-quantity {
            background-color: var(--border-color);
            color: var(--text-color);
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 50px;
        }

        .product-subtotal {
            font-weight: 600;
            color: var(--heading-color);
        }

        /* Botones */
        .btn {
            border-radius: 50px;
            font-size: 13px;
            padding: 8px 16px;
            font-weight: 500;
            box-shadow: none;
        }

        .btn-outline-secondary {
            border-color: #ddd;
            color: #666;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #e56464;
            border-color: #e56464;
        }

        .btn-danger {
            background-color: #fff;
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        /* Información del pedido */
        .info-label {
            font-weight: 600;
            color: var(--heading-color);
            font-size: 13px;
        }

        .info-value {
            color: var(--text-color);
            font-size: 13px;
        }

        .divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 15px 0;
        }

        .alert-info {
            background-color: rgba(0, 123, 255, 0.05);
            border-color: rgba(0, 123, 255, 0.1);
            color: #dd7070;
            border-radius: var(--radius);
            font-size: 13px;
        }

        .total-price {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .progress-steps {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .progress-bg, .progress-fill {
                display: none;
            }

            .progress-step {
                max-width: none;
                margin-bottom: 5px;
            }

            .product-info, .product-pricing {
                text-align: left !important;
            }

            .product-pricing {
                margin-top: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <a href="{{ route('index') }}" class="site-logo-link d-flex align-items-center justify-content-center">
            <h1 class="sitename mb-0" style="font-family: 'Dancing Script', cursive; font-size: 2rem;">Mi sueño
                <span style="font-size: 2rem; margin-right: 5px;"> dulce</span>
            </h1>
            <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="site-logo" id="siteLogo">
        </a>
    </div>

    <!-- Botón de cambio de tema -->
    <button id="theme-toggle" class="theme-toggle-btn">
        <i class="bi bi-moon-fill"></i>
    </button>

    <div class="pedido-detalle-page py-4">
        <div class="content-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="page-title">
                    Pedido #{{ $pedido->codigo ?? $pedido->id }}
                </h1>
                <a href="{{ route('perfil') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>

            <!-- Barra de estado del pedido -->
            <div class="card mb-4">
                <div class="card-body">

                    <!-- Rastreador de progreso minimalista -->
                    <div class="order-progress-tracker" id="orderProgressTracker">
                        <div class="progress-bg"></div>
                        <div class="progress-fill" id="progressFill"></div>

                        <div class="progress-steps">
                            <div class="progress-step" data-step="pendiente">
                                <div class="step-icon">
                                    <i class="bi bi-1-circle"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Pagado</div>
                                    <div class="step-desc">Pedido recibido</div>
                                </div>
                            </div>
                            <div class="progress-step" data-step="confirmado">
                                <div class="step-icon">
                                    <i class="bi bi-2-circle"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Aceptado</div>
                                    <div class="step-desc">Confirmado</div>
                                </div>
                            </div>
                            <div class="progress-step" data-step="en_proceso">
                                <div class="step-icon">
                                    <i class="bi bi-3-circle"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Elaborando</div>
                                    <div class="step-desc">En preparación</div>
                                </div>
                            </div>
                            <div class="progress-step" data-step="terminado">
                                <div class="step-icon">
                                    <i class="bi bi-4-circle"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Listo</div>
                                    <div class="step-desc">Para recoger</div>
                                </div>
                            </div>
                            <div class="progress-step" data-step="recogido">
                                <div class="step-icon">
                                    <i class="bi bi-5-circle"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Recogido</div>
                                    <div class="step-desc">Completado</div>
                                </div>
                            </div>
                            <div class="progress-step cancelado" data-step="cancelado" style="display: none;">
                                <div class="step-icon">
                                    <i class="bi bi-x"></i>
                                </div>
                                <div class="step-text">
                                    <div class="step-label">Cancelado</div>
                                    <div class="step-desc">Pedido cancelado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón Cancelar pedido movido aquí, debajo de la barra de progreso -->
                    @if($pedido->estado == 'pagado')
                        <div class="mt-4 text-center">
                            <form action="{{ route('perfil.cancelar-pedido', $pedido->id) }}" method="POST" class="cancel-order-form">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.')">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar pedido
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Información del pedido -->
                <div class="col-lg-5 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Detalles del pedido</h5>

                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Número:</div>
                                    <div class="col-7 info-value">#{{ $pedido->codigo ?? $pedido->id }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Fecha:</div>
                                    <div class="col-7 info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Cliente:</div>
                                    <div class="col-7 info-value">{{ $pedido->nombre }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Email:</div>
                                    <div class="col-7 info-value">{{ $pedido->email }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Teléfono:</div>
                                    <div class="col-7 info-value">{{ $pedido->telefono }}</div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col-5 info-label">Pago:</div>
                                    <div class="col-7 info-value">
                                        @if($pedido->metodo_pago == 'tarjeta')
                                            <i class="bi bi-credit-card me-1"></i> Tarjeta
                                        @elseif($pedido->metodo_pago == 'paypal')
                                            <i class="bi bi-paypal me-1"></i> PayPal
                                        @elseif($pedido->metodo_pago == 'bizum')
                                            <i class="bi bi-phone me-1"></i> Bizum
                                        @elseif($pedido->metodo_pago == 'transferencia')
                                            <i class="bi bi-bank me-1"></i> Transferencia
                                        @else
                                            <i class="bi bi-cash me-1"></i> {{ ucfirst($pedido->metodo_pago) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 info-label">Total:</div>
                                    <div class="col-7">
                                        <span class="total-price">€{{ number_format($pedido->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Productos del pedido -->
                <div class="col-lg-7 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Productos ({{ count($pedido->detalles) }})</h5>

                            <div class="products-list">
                                @foreach($pedido->detalles as $detalle)
                                    <div class="product-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-8 product-info">
                                                <div class="product-name">{{ $detalle->nombre_producto }}</div>
                                                <div class="product-price">€{{ number_format($detalle->precio_unitario, 2) }} / unidad</div>
                                            </div>
                                            <div class="col-md-4 text-md-end product-pricing">
                                                <div class="d-flex justify-content-md-end align-items-center">
                                                    <span class="product-quantity me-2">{{ $detalle->cantidad }} uds</span>
                                                    <span class="product-subtotal">€{{ number_format($detalle->subtotal, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="divider"></div>

                            <div class="text-end">
                                <div class="mb-1">Total</div>
                                <div class="total-price fs-5">€{{ number_format($pedido->total, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas adicionales -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Recibirás notificaciones por email cuando el estado de tu pedido cambie.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el estado del pedido actual
            const estadoActual = '{{ strtolower($pedido->estado) }}';
            const progressTracker = document.getElementById('orderProgressTracker');
            const progressSteps = progressTracker.querySelectorAll('.progress-step');
            const progressFill = document.getElementById('progressFill');

            // Definir el orden de los pasos
            const pasos = ['pendiente', 'confirmado', 'en_proceso', 'terminado', 'recogido'];
            const pasosTotal = pasos.length - 1; // -1 porque contamos desde 0

            // Si el estado es cancelado, mostrar solo ese paso
            if (estadoActual === 'cancelado') {
                // Ocultar todos los pasos normales
                progressSteps.forEach(step => {
                    if (step.dataset.step !== 'cancelado') {
                        step.style.display = 'none';
                    } else {
                        step.style.display = 'flex';
                        step.classList.add('active');
                        step.classList.add('current');
                    }
                });

                // Cambiar el color de la barra de progreso a rojo
                progressFill.style.width = '100%';
                progressFill.style.background = '#dc3545';
            } else {
                // Para otros estados, marcar todos los pasos hasta el actual
                const estadoIndex = pasos.indexOf(estadoActual);

                if (estadoIndex !== -1) {
                    // Calcular el porcentaje de progreso
                    const progressPercent = (estadoIndex / pasosTotal) * 100;
                    progressFill.style.width = progressPercent + '%';

                    // Actualizar clases de los pasos
                    progressSteps.forEach(step => {
                        // Ocultar paso cancelado
                        if (step.dataset.step === 'cancelado') {
                            step.style.display = 'none';
                            return;
                        }

                        const stepIndex = pasos.indexOf(step.dataset.step);

                        if (stepIndex <= estadoIndex) {
                            step.classList.add('active');
                        }

                        if (stepIndex === estadoIndex) {
                            step.classList.add('current');
                        }
                    });
                }
            }
        });
    </script>
    
    <!-- Script para alternar entre modo claro y oscuro -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const icon = themeToggle.querySelector('i');
            const favicon = document.getElementById('favicon');
            const siteLogo = document.getElementById('siteLogo');
            
            // Comprobar el tema almacenado o usar el modo claro por defecto
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            // Aplicar el tema guardado al cargar la página
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
                // Cambiar favicon y logo
                favicon.href = "{{ asset('img/logo/img-ico.ico') }}";
                siteLogo.src = "{{ asset('img/logo/logo_blanco.png') }}";
            }
            
            // Manejar el clic en el botón de cambio de tema
            themeToggle.addEventListener('click', function() {
                // Obtener el tema actual
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                
                // Cambiar al tema opuesto
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                // Guardar preferencia en localStorage
                localStorage.setItem('theme', newTheme);
                
                // Aplicar el nuevo tema
                document.documentElement.setAttribute('data-theme', newTheme);
                
                // Actualizar el icono del botón
                if (newTheme === 'dark') {
                    icon.classList.remove('bi-moon-fill');
                    icon.classList.add('bi-sun-fill');
                    // Cambiar favicon y logo en modo oscuro
                    favicon.href = "{{ asset('img/logo/img-ico.ico') }}";
                    siteLogo.src = "{{ asset('img/logo/logo_blanco.png') }}";
                } else {
                    icon.classList.remove('bi-sun-fill');
                    icon.classList.add('bi-moon-fill');
                    // Restaurar favicon y logo originales
                    favicon.href = "{{ asset('img/favicon.png') }}";
                    siteLogo.src = "{{ asset('img/logo/logo.png') }}";
                }
            });
        });
    </script>
</body>
</html>

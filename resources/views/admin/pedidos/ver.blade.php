<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido #{{ $pedido->id }} - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            transition: all 0.3s ease;
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
        }

        .nav-link {
            padding: 12px 20px;
            color: var(--text-color);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
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

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: all 0.3s ease;
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

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #e56464;
            border-color: #e56464;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
        }

        .sidebar-footer a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar-footer a:hover {
            color: var(--primary-color);
        }

        .sidebar-footer i {
            margin-right: 10px;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .content {
                margin-left: 0;
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
            }
            body.sidebar-open .content {
                margin-left: var(--sidebar-width);
            }
            .toggle-sidebar {
                display: block !important;
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
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-details {
            margin-left: 15px;
        }

        .product-name {
            font-weight: 600;
            color: var(--heading-color);
            margin-bottom: 3px;
        }

        .product-price {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
        }

        .status-confirmado {
            background-color: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }

        .status-en_proceso {
            background-color: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-terminado {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-cancelado {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .order-summary {
            background-color: rgba(255, 112, 112, 0.05);
            border-radius: 10px;
            padding: 15px;
        }

        .order-total {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--heading-color);
            margin-top: 1rem;
            margin-bottom: 0.3rem;
        }

        .info-value {
            margin-bottom: 1rem;
        }

        .order-status-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed var(--border-color);
        }
    </style>
</head>
<body>
    <!-- Toggle Sidebar Button (visible on mobile) -->
    <div class="toggle-sidebar">
        <i class="bi bi-list"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sitename">Mi sueño dulce</h1>
            <div>Panel de Administración</div>
        </div>
        <div class="sidebar-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
                    <a class="nav-link active" href="{{ route('admin.pedidos') }}">
                        <i class="bi bi-bag"></i> Pedidos
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
        <div class="admin-toolbar">
            <h3 class="mb-0">Detalles del Pedido #{{ $pedido->codigo ?? $pedido->id }}</h3>
            <div>
                <a href="{{ route('admin.pedidos') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Pedidos
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-box2"></i> Productos del Pedido
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Tamaño</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $detalle)
                                        <tr>
                                            <td>
                                                <div class="product-info">
                                                    @if($detalle->producto && $detalle->producto->imagen)
                                                        <img src="{{ asset('img/productos/' . $detalle->producto->imagen) }}" alt="{{ $detalle->nombre_producto }}" class="product-image" style="max-width: 60px; max-height: 60px; border-radius: 5px;">
                                                    @else
                                                        <div class="product-image bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 5px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="product-details">
                                                        <div class="product-name">{{ $detalle->nombre_producto }}</div>
                                                        <div class="product-price">ID: {{ $detalle->producto_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    @if($detalle->tamano == 'normal')
                                                        Normal
                                                    @elseif($detalle->tamano == 'grande')
                                                        Grande
                                                    @elseif($detalle->tamano == 'muygrande')
                                                        Muy Grande
                                                    @else
                                                        {{ ucfirst($detalle->tamano ?? 'Normal') }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>€{{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td>€{{ number_format($detalle->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                        <td class="order-total">€{{ number_format($pedido->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Customer Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person"></i> Información del Cliente
                    </div>
                    <div class="card-body">
                        <div class="info-label">Nombre:</div>
                        <div class="info-value">{{ $pedido->nombre }}</div>

                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $pedido->email }}</div>

                        <div class="info-label">Teléfono:</div>
                        <div class="info-value">{{ $pedido->telefono ?? 'No proporcionado' }}</div>

                        <div class="info-label">Usuario ID:</div>
                        <div class="info-value">{{ $pedido->user_id }}</div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Información de la Compra
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-label">ID del Pedido</div>
                                <div class="info-value">{{ $pedido->id }}</div>

                                <div class="info-label">Código del Pedido</div>
                                <div class="info-value">#{{ $pedido->codigo ?? $pedido->id }}</div>

                                <div class="info-label">Fecha de Compra</div>
                        <div class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>

                                <div class="info-label">Método de Pago</div>
                                <div class="info-value">{{ ucfirst($pedido->metodo_pago) }}</div>
                        </div>
                            <div class="col-md-6">
                        <div class="info-label">Estado:</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </div>

                        <div class="order-summary mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>€{{ number_format($pedido->total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><strong>Total:</strong></span>
                                <span class="order-total">€{{ number_format($pedido->total, 2) }}</span>
                            </div>
                        </div>

                        @if ($pedido->notas)
                            <div class="mt-3">
                                <h6>Notas sobre el pedido:</h6>
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <p class="card-text" style="white-space: pre-line;">{{ $pedido->notas }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="order-status-form mt-4">
                            <form action="{{ route('admin.pedidos.actualizar-estado', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Actualizar Estado:</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="confirmado" {{ $pedido->estado == 'confirmado' ? 'selected' : '' }}>Aceptado</option>
                                        <option value="en_proceso" {{ $pedido->estado == 'en_proceso' ? 'selected' : '' }}>Elaborando</option>
                                        <option value="terminado" {{ $pedido->estado == 'terminado' ? 'selected' : '' }}>Terminado - Listo para recoger</option>
                                        <option value="recogido" {{ $pedido->estado == 'recogido' ? 'selected' : '' }}>Recogido</option>
                                        <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Al cambiar el estado se enviará un correo automáticamente al cliente informándole de la actualización.
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Nota interna:</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="2" placeholder="Añadir nota sobre este pedido (visible sólo para administradores)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check2-circle me-1"></i> Actualizar Estado
                                </button>
                            </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar on mobile
            const toggleBtn = document.querySelector('.toggle-sidebar');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-open');
                });
            }
        });
    </script>
</body>
</html>

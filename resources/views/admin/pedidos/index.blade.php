@extends('admin.layouts.admin')

@section('title', 'Gestión de Pedidos')

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
</style>

<div class="page-header">
    <h1 class="page-title">Gestión de Pedidos</h1>
    <p class="text-muted">Administra y gestiona los pedidos realizados en la tienda.</p>
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

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-bag-fill me-2"></i> Lista de Pedidos
        </div>
        <div class="d-flex gap-3">
            <form action="{{ route('admin.pedidos') }}" method="GET" class="search-container position-relative">
                <i class="bi bi-search search-icon" style="margin-top: 9px;"></i>
                <input type="text" name="search" id="searchPedido" class="form-control" 
                    placeholder="Buscar pedido..." value="{{ request('search') }}" style="margin-bottom: -14px;">
            </form>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills filter-tabs" style="margin-bottom: 1.5rem;">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') || request('status') == 'all' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'all'])) }}">
                    Todos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'pendiente' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'pendiente'])) }}">
                    Pendiente
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'confirmado' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'confirmado'])) }}">
                    Aceptado
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'en_proceso' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'en_proceso'])) }}">
                    Elaborando
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'terminado' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'terminado'])) }}">
                    Terminado
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'recogido' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'recogido'])) }}">
                    Recogido
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'cancelado' ? 'active' : '' }}" 
                   href="{{ route('admin.pedidos', array_merge(request()->except('status'), ['status' => 'cancelado'])) }}">
                    Cancelado
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Método de Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr data-status="{{ strtolower($pedido->estado) }}">
                            <td class="order-id">#{{ $pedido->codigo ?? $pedido->id }}</td>
                            <td>{{ $pedido->user->name }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td class="order-total">€{{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($pedido->metodo_pago) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.pedidos.ver', $pedido->codigo ?? $pedido->id) }}" class="btn btn-link admin-action-btn edit" title="Editar pedido">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.pedidos.eliminar', $pedido->codigo ?? $pedido->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link admin-action-btn delete" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($pedidos) == 0)
            <div class="text-center p-4">
                <i class="bi bi-bag-x" style="font-size: 3rem; color: #e8e8e8;"></i>
                <p class="mt-3">No hay pedidos para mostrar.</p>
            </div>
        @endif

        <div class="d-flex justify-content-center mt-4">
            {{ $pedidos->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

<style>
.filter-tabs {
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 0;
    gap: 10px;
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    justify-content: space-between;
}

.filter-tabs .nav-item {
    flex: 1;
    text-align: center;
    display: flex;
}

.filter-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 8px 16px;
    font-size: 0.95rem;
    background: none;
    transition: all 0.2s ease;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.filter-tabs .nav-link:hover {
    color: #ff7070;
}

.filter-tabs .nav-link::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #ff7070;
    transform: scaleX(0);
    transition: transform 0.2s ease;
}

.filter-tabs .nav-link:hover::after,
.filter-tabs .nav-link.active::after {
    transform: scaleX(1);
}

/* Sobreescribir colores de Bootstrap para nav-pills */
.nav-pills .nav-link.active,
.nav-pills .show > .nav-link,
.nav-pills .nav-link:hover {
    background-color: transparent !important;
    color: #ff7070 !important;
    border: none !important;
}

.nav-pills .nav-link:focus {
    box-shadow: none !important;
}

/* Eliminar cualquier estilo adicional que pueda interferir */
.nav-pills .nav-link::after,
.nav-pills .nav-link::before {
    display: none !important;
}

/* Mantener los estilos existentes de los badges de estado */
.status-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pendiente { background-color: #ffd700; color: #000; }
.status-confirmado { background-color: #87ceeb; color: #000; }
.status-en_proceso { background-color: #ff7f50; color: #fff; }
.status-terminado { background-color: #90ee90; color: #000; }
.status-recogido { background-color: #32cd32; color: #fff; }
.status-cancelado { background-color: #ff6b6b; color: #fff; }

.pagination {
    margin-bottom: 0;
}

.pagination .page-item .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    color: #ff7070;
}

.pagination .page-item.active .page-link {
    background-color: #ff7070;
    border-color: #ff7070;
    color: white;
}

.pagination .page-item .page-link:hover {
    color: #ff5555;
    background-color: #e9ecef;
    border-color: #dee2e6;
    text-decoration: none;
}

.pagination .page-item .page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
    z-index: 3;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.search-container {
    width: 300px;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 55%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
}

#searchPedido {
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 8px 15px 8px 40px;
    width: 100%;
    background-color: #fff;
    height: 38px;
    font-size: 0.9rem;
    transition: all 0.2s ease-in-out;
}

#searchPedido:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
    border-color: #ff7070;
}

.input-group {
    background-color: transparent;
}

.input-group-text {
    display: none;
}

.card-header {
    padding: 1rem;
    background-color: white;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.floating-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Confirmación de eliminación
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar pedido?',
                html: '¿Estás seguro de que deseas eliminar este pedido?<br><br>Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff7070',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-title',
                    htmlContainer: 'swal-text',
                    confirmButton: 'swal-confirm',
                    cancelButton: 'swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush

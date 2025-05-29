@extends('admin.layouts.admin')

@section('title', 'Administración de Reseñas')

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

    #searchResena {
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 8px 15px 8px 40px;
        width: 100%;
        background-color: #fff;
        height: 38px;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }

    #searchResena:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        border-color: #ff7070;
    }

    /* Estilos para la paginación */
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

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Gestión de Reseñas</h1>
    <p class="text-muted">Administra las reseñas de los clientes de la tienda.</p>
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
            <i class="bi bi-star-fill me-2"></i> Lista de Reseñas
        </div>
        <div class="d-flex gap-3">
            <form action="{{ route('admin.resenas') }}" method="GET" class="search-container position-relative">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" id="searchResena" class="form-control" 
                    placeholder="Buscar reseña..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills filter-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}" 
                   href="{{ route('admin.resenas', array_merge(request()->except('filter'), ['filter' => 'all'])) }}">
                    Todas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'pending' ? 'active' : '' }}" 
                   href="{{ route('admin.resenas', array_merge(request()->except('filter'), ['filter' => 'pending'])) }}">
                    Pendientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'approved' ? 'active' : '' }}" 
                   href="{{ route('admin.resenas', array_merge(request()->except('filter'), ['filter' => 'approved'])) }}">
                    Aprobadas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') == 'rejected' ? 'active' : '' }}" 
                   href="{{ route('admin.resenas', array_merge(request()->except('filter'), ['filter' => 'rejected'])) }}">
                    Rechazadas
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Valoración</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resenas as $resena)
                        <tr>
                            <td>{{ $resena->id }}</td>
                            <td>
                                <a href="{{ route('producto.detalle', $resena->producto_id) }}" target="_blank">
                                    {{ $resena->producto->nombre ?? 'Producto no disponible' }}
                                </a>
                            </td>
                            <td>{{ $resena->user->name ?? 'Usuario desconocido' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $resena->rating }}</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $resena->rating)
                                            <i class="bi bi-star-fill" style="color: var(--primary-color);"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>{{ $resena->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($resena->is_approved)
                                    <span class="status-badge" style="background-color: rgba(var(--primary-color-rgb), 0.15); color: var(--primary-color);">Aprobada</span>
                                @else
                                    <span class="status-badge status-pendiente">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.resenas.editar', $resena->id) }}" class="btn btn-link admin-action-btn edit" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.resenas.eliminar', $resena->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link admin-action-btn delete" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-star fs-1 text-muted mb-3"></i>
                                    <p class="mb-0">No se encontraron reseñas</p>
                                    @if($search)
                                        <a href="{{ route('admin.resenas') }}" class="btn btn-link">Volver a todas las reseñas</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $resenas->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>

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
                    title: '¿Eliminar reseña?',
                    html: '¿Estás seguro de que deseas eliminar esta reseña?<br><br>Esta acción no se puede deshacer.',
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
@endsection 
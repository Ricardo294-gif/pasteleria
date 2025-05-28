@extends('admin.layouts.admin')

@section('title', 'Administración de Reseñas')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestión de Reseñas</h1>
    <p class="text-muted">Administra las reseñas de los clientes de la tienda.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-star"></i> Reseñas de Clientes
        </div>
        <div class="search-container position-relative">
            <form action="{{ route('admin.resenas') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Buscar reseñas..." value="{{ $search }}">
                <button type="submit" class="btn ms-2" style="background-color: var(--primary-color); color: white;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="card-body">
        <div class="filters-container">
            <a href="{{ route('admin.resenas', ['filter' => 'all']) }}" class="filter-btn {{ $filter == 'all' ? 'active' : '' }}">
                Todas
            </a>
            <a href="{{ route('admin.resenas', ['filter' => 'pending']) }}" class="filter-btn {{ $filter == 'pending' ? 'active' : '' }}">
                Pendientes
            </a>
            <a href="{{ route('admin.resenas', ['filter' => 'approved']) }}" class="filter-btn {{ $filter == 'approved' ? 'active' : '' }}">
                Aprobadas
            </a>
            <a href="{{ route('admin.resenas', ['filter' => 'high-rating']) }}" class="filter-btn {{ $filter == 'high-rating' ? 'active' : '' }}">
                ≥ 4 ★
            </a>
            <a href="{{ route('admin.resenas', ['filter' => 'low-rating']) }}" class="filter-btn {{ $filter == 'low-rating' ? 'active' : '' }}">
                ≤ 2 ★
            </a>
        </div>

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
                                    <a href="{{ route('admin.resenas.ver', $resena->id) }}" class="btn btn-link admin-action-btn view" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.resenas.editar', $resena->id) }}" class="btn btn-link admin-action-btn edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.resenas.toggle-aprobacion', $resena->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-link admin-action-btn {{ $resena->is_approved ? 'delete' : 'view' }}" title="{{ $resena->is_approved ? 'Desaprobar' : 'Aprobar' }}">
                                            <i class="bi {{ $resena->is_approved ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.resenas.eliminar', $resena->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link admin-action-btn delete" title="Eliminar">
                                            <i class="bi bi-trash"></i>
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
    </div>
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $resenas->appends(['search' => $search, 'filter' => $filter])->links() }}
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmación de eliminación
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de que deseas eliminar esta reseña?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection 
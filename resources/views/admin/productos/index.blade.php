@extends('admin.layouts.admin')

@section('title', 'Gestión de Productos')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestión de Productos</h1>
    <p class="text-muted">Administra el catálogo de productos de la tienda.</p>
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
            <i class="bi bi-box-seam me-2"></i> Catálogo de Productos
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.productos.crear') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Nuevo Producto
            </a>
            <div class="search-container position-relative">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="table-responsive" class="form-control" placeholder="Buscar producto...">
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills filter-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" data-category="all" href="javascript:void(0)">
                    Todos
                </a>
            </li>
            @foreach($categorias as $categoria)
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" data-category="{{ $categoria->nombre }}" href="javascript:void(0)">
                    {{ $categoria->nombre }}
                </a>
            </li>
            @endforeach
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" data-category="Sin categoría" href="javascript:void(0)">
                    Sin categoría
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $productosUnicos = collect($productos)->unique('id');
                    @endphp
                    @foreach($productosUnicos as $producto)
                    <tr data-category="{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}">
                        <td>
                            <img src="{{ asset(file_exists(public_path('storage/' . $producto->imagen)) ? 'storage/' . $producto->imagen : (file_exists(public_path('img/productos/' . $producto->imagen)) ? 'img/productos/' . $producto->imagen : 'img/productos/default.jpg')) }}" 
                                alt="{{ $producto->nombre }}" 
                                class="admin-thumbnail" 
                                style="max-width: 100px; height: auto; object-fit: cover;">
                        </td>
                        <td class="align-middle">{{ $producto->nombre }}</td>
                        <td class="align-middle">{{ \Illuminate\Support\Str::limit($producto->descripcion, 50, '...') }}</td>
                        <td class="align-middle">{{ $producto->precio }} €</td>
                        <td class="align-middle">{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</td>
                        <td class="align-middle">
                            <a href="{{ route('admin.productos.editar', $producto->id) }}" class="btn admin-action-btn edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" class="btn btn-link admin-action-btn delete" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $producto->id }}">
                                <i class="bi bi-trash"></i>
                            </button>

                            <!-- Modal de confirmación para eliminar -->
                            <div class="modal fade" id="deleteModal{{ $producto->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $producto->id }}" aria-hidden="true" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $producto->id }}">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                Confirmar eliminación
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <img src="{{ asset(file_exists(public_path('storage/' . $producto->imagen)) ? 'storage/' . $producto->imagen : (file_exists(public_path('img/productos/' . $producto->imagen)) ? 'img/productos/' . $producto->imagen : 'img/productos/default.jpg')) }}" 
                                                    alt="{{ $producto->nombre }}" 
                                                    style="max-height: 100px; max-width: 100%; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            </div>
                                            <p class="text-center fs-5 mb-3">{{ $producto->nombre }}</p>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <p class="mb-1"><strong>ID:</strong> {{ $producto->id }}</p>
                                                    <p class="mb-1"><strong>Precio:</strong> {{ $producto->precio }} €</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1"><strong>Categoría:</strong> {{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</p>
                                                </div>
                                            </div>
                                            <p>¿Estás seguro de que deseas eliminar este producto?</p>
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                <strong>Advertencia:</strong> Esta acción no se puede deshacer y eliminará permanentemente este producto del catálogo.
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center gap-2">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i>Cancelar
                                            </button>
                                            <form action="{{ route('admin.productos.eliminar', $producto->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash me-1"></i>Sí, eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

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

            // Código existente para filtrado
            const searchInput = document.getElementById('table-responsive');
            const categoryLinks = document.querySelectorAll('.nav-link[data-category]');
            const tableRows = document.querySelectorAll('tbody tr');
            let currentCategory = 'all';

            // Función para filtrar productos
            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const productDescription = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const productCategory = row.getAttribute('data-category');
                    
                    const matchesSearch = productName.includes(searchTerm) || productDescription.includes(searchTerm);
                    const matchesCategory = currentCategory === 'all' || productCategory === currentCategory;
                    
                    row.style.display = matchesSearch && matchesCategory ? '' : 'none';
                });
            }

            // Event listener para la búsqueda
            searchInput.addEventListener('input', filterProducts);

            // Event listeners para los filtros de categoría
            categoryLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Actualizar clases activas
                    categoryLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    // Actualizar categoría actual y filtrar
                    currentCategory = this.getAttribute('data-category');
                    filterProducts();
                });
            });
        });
    </script>
@endpush

<style>
/* Estilos para alertas flotantes */
.floating-alerts {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
}

.floating-alert {
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
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

/* Estilos para los botones de acción */
.admin-action-btn {
    color: #6c757d;
    transition: color 0.3s ease;
}

.admin-action-btn:hover {
    color: #ff7070 !important;
    background: none !important;
}

.admin-action-btn.edit:hover {
    color: #ff7070 !important;
}

/* Estilos para el input de búsqueda */
.search-container .form-control:focus {
    border-color: #ff7070 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25) !important;
}

.form-control:focus {
    border-color: #ff7070 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25) !important;
}

/* Estilos para la tabla de productos */
.table td {
    vertical-align: middle;
    margin-top: -10px;
}

.admin-thumbnail {
    max-width: 100px;
    height: auto;
    object-fit: cover;
    margin-top: -10px;
    margin-bottom: -10px;
}

.table tr {
    line-height: 1.2;
}
</style>

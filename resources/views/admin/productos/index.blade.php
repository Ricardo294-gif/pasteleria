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
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Filtro de categorías -->
        <div class="mb-4">
            <label for="categoriaFilter" class="form-label">Filtrar por categoría:</label>
            <select class="form-select" id="categoriaFilter">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
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
                    @foreach($productos as $producto)
                    <tr data-categoria-id="{{ $producto->categoria_id ?? '' }}">
                        <td>
                            <img src="{{ asset('img/productos/' . ($producto->imagen ?? 'default.jpg')) }}" 
                                alt="{{ $producto->nombre }}" 
                                class="img-thumbnail"
                                style="max-width: 100px;">
                        </td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($producto->descripcion, 100) }}</td>
                        <td>€{{ number_format($producto->precio, 2) }}</td>
                        <td>{{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.productos.editar', $producto->id) }}" class="btn btn-link admin-action-btn edit" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form class="delete-form m-0" action="{{ route('admin.productos.eliminar', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-link admin-action-btn delete" title="Eliminar" onclick="confirmarEliminacion(this.form, '{{ $producto->nombre }}')">
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

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoriaFilter = document.getElementById('categoriaFilter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategoria = categoriaFilter.value;

        tableRows.forEach(row => {
            const nombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const descripcion = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const categoriaId = row.getAttribute('data-categoria-id');

            const matchesSearch = nombre.includes(searchTerm) || descripcion.includes(searchTerm);
            const matchesCategoria = !selectedCategoria || categoriaId === selectedCategoria;

            row.style.display = matchesSearch && matchesCategoria ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    categoriaFilter.addEventListener('change', filterTable);

    // Auto-cerrar alertas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 3000);
    });
});

function confirmarEliminacion(form, nombreProducto) {
    Swal.fire({
        title: '¿Eliminar producto?',
        html: `¿Estás seguro de que deseas eliminar el producto "${nombreProducto}"?<br><br>Esta acción no se puede deshacer.`,
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
            form.submit();
        }
    });
}
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
    padding: 0;
    margin: 0;
    line-height: 1;
}

.admin-action-btn:hover {
    color: #ff7070 !important;
    background: none !important;
    text-decoration: none;
}

.admin-action-btn.edit:hover {
    color: #ff7070 !important;
}

.admin-action-btn.delete:hover {
    color: #dc3545 !important;
}

.admin-action-btn i {
    font-size: 1.1rem;
}

/* Eliminar estilos de botón por defecto */
.btn-link {
    text-decoration: none;
    border: none;
    background: none;
    padding: 0;
}

.btn-link:focus {
    box-shadow: none;
}

.delete-form {
    margin: 0;
    padding: 0;
    display: inline;
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

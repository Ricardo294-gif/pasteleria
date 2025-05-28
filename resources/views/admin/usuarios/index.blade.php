@extends('admin.layouts.admin')

@section('title', 'Gestión de Usuarios')

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
    <h1 class="page-title">Gestión de Usuarios</h1>
    <p class="text-muted">Administra y gestiona los usuarios registrados en la plataforma.</p>
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
            <i class="bi bi-people-fill me-2"></i> Lista de Usuarios
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Crear Usuario
            </a>
            <form action="{{ route('admin.usuarios') }}" method="GET" class="search-container position-relative">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" id="searchUser" class="form-control" 
                       placeholder="Buscar usuario..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr data-role="{{ $usuario->is_admin ? 'admin' : 'client' }}">
                            <td>
                                <div class="user-info">
                                    <div>{{ $usuario->name }} {{ $usuario->apellido }}</div>
                                </div>
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefono ?? 'No disponible' }}</td>
                            <td>
                                @if($usuario->is_admin)
                                    <span class="admin-badge">Administrador</span>
                                @else
                                    Cliente
                                @endif
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.usuarios.editar', $usuario->id) }}" class="btn btn-link admin-action-btn edit" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form class="delete-form m-0" action="{{ route('admin.usuarios.eliminar', $usuario->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link admin-action-btn delete" title="Eliminar" onclick="confirmarEliminacion(this.form, '{{ $usuario->name }}')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center ps-3">
                {{ $usuarios->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
.search-container {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

#searchUser {
    padding-left: 35px;
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

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn.active {
    background-color: #ff7070;
    border-color: #ff7070;
    color: white;
}

.admin-badge {
    background-color: #ff7070;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.user-info {
    display: flex;
    align-items: center;
}

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
</style>
@endsection

@push('scripts')
<script>
function confirmarEliminacion(form, nombreUsuario) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        html: `¿Estás seguro de que deseas eliminar al usuario <strong>${nombreUsuario}</strong>?<br><br>Esta acción no se puede deshacer.`,
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

    // Búsqueda con delay
    const searchForm = document.querySelector('.search-container');
    const searchInput = document.getElementById('searchUser');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500);
    });
});
</script>

<style>
.swal-custom-popup {
    border-radius: 15px;
    padding: 20px;
}

.swal-title {
    color: #37373f;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.swal-text {
    color: #4a4a4a;
    font-size: 1rem;
    line-height: 1.5;
}

.swal-confirm {
    padding: 8px 25px !important;
    font-size: 1rem !important;
}

.swal-cancel {
    padding: 8px 25px !important;
    font-size: 1rem !important;
}
</style>
@endpush

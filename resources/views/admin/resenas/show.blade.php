@extends('admin.layouts.admin')

@section('title', 'Detalles de Reseña')

@section('content')
<div class="page-header">
    <h1 class="page-title">Detalles de Reseña</h1>
    <p class="text-muted">Información detallada sobre la reseña del cliente.</p>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-star"></i> Reseña #{{ $resena->id }}
        </div>
        <div>
            @if($resena->is_approved)
                <span class="status-badge" style="background-color: rgba(var(--primary-color-rgb), 0.15); color: var(--primary-color);">Aprobada</span>
            @else
                <span class="status-badge status-pendiente">Pendiente</span>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Información del Producto</h5>
                <div class="mb-4">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 130px;">Producto</th>
                            <td>
                                <a href="{{ route('producto.detalle', $resena->producto_id) }}" target="_blank">
                                    {{ $resena->producto->nombre ?? 'Producto no disponible' }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoría</th>
                            <td>{{ $resena->producto->categoria ?? 'No especificada' }}</td>
                        </tr>
                        <tr>
                            <th>Precio</th>
                            <td>€{{ number_format($resena->producto->precio ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h5>Información del Usuario</h5>
                <div class="mb-4">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 130px;">Usuario</th>
                            <td>{{ $resena->user->name ?? 'Usuario desconocido' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $resena->user->email ?? 'Email no disponible' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de registro</th>
                            <td>{{ $resena->user->created_at ? $resena->user->created_at->format('d/m/Y') : 'No disponible' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h5>Detalles de la Reseña</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Valoración:</strong>
                            <div class="d-flex align-items-center mt-1">
                                <span class="me-2 fs-5">{{ $resena->rating }} / 5</span>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $resena->rating)
                                        <i class="bi bi-star-fill fs-5" style="color: var(--primary-color);"></i>
                                    @else
                                        <i class="bi bi-star text-muted fs-5"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Fecha de creación:</strong>
                            <p>{{ $resena->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Última actualización:</strong>
                            <p>{{ $resena->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div>
                            <strong>Comentario:</strong>
                            <div class="border p-3 rounded bg-light mt-2">
                                {{ $resena->comment }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('admin.resenas.editar', $resena->id) }}" class="btn" style="background-color: var(--primary-color); color: white;">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <form action="{{ route('admin.resenas.toggle-aprobacion', $resena->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi {{ $resena->is_approved ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                        {{ $resena->is_approved ? 'Desaprobar' : 'Aprobar' }}
                    </button>
                </form>
            </div>
            <form action="{{ route('admin.resenas.eliminar', $resena->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<div class="text-end mt-3">
    <a href="{{ route('admin.resenas') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver a las reseñas
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmación de eliminación
        const deleteForm = document.querySelector('.delete-form');
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar esta reseña?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
@endsection 
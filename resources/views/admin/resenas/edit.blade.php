@extends('admin.layouts.admin')

@section('title', 'Editar Reseña')

@section('content')
<div class="page-header">
    <h1 class="page-title">Editar Reseña</h1>
    <p class="text-muted">Modifica los detalles de la reseña del cliente.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil"></i> Editar Reseña #{{ $resena->id }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.resenas.actualizar', $resena->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Información del Producto
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Producto</label>
                                <input type="text" class="form-control" value="{{ $resena->producto->nombre ?? 'Producto no disponible' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Información del Usuario
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" value="{{ $resena->user->name ?? 'Usuario desconocido' }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ $resena->user->email ?? 'Email no disponible' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    Contenido de la Reseña
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Valoración</label>
                        <div class="rating-input d-flex align-items-center">
                            <select name="rating" id="rating" class="form-select me-3" style="max-width: 100px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $resena->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="rating-stars" id="ratingStars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $resena->rating ? 'bi-star-fill' : 'bi-star' }} fs-5" style="color: var(--primary-color);"></i>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentario</label>
                        <textarea name="comment" id="comment" rows="6" class="form-control" required>{{ old('comment', $resena->comment) }}</textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input type="hidden" name="is_approved" value="0">
                        <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" {{ $resena->is_approved ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_approved">Reseña aprobada</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn" style="background-color: var(--primary-color); color: white;">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('admin.resenas.ver', $resena->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
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
        // Actualizar estrellas según la valoración seleccionada
        const ratingSelect = document.getElementById('rating');
        const ratingStars = document.getElementById('ratingStars');

        ratingSelect.addEventListener('change', function() {
            const rating = parseInt(this.value);
            let starsHTML = '';
            
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsHTML += '<i class="bi bi-star-fill fs-5" style="color: var(--primary-color);"></i>';
                } else {
                    starsHTML += '<i class="bi bi-star fs-5" style="color: var(--primary-color);"></i>';
                }
            }
            
            ratingStars.innerHTML = starsHTML;
        });
    });
</script>
@endpush
@endsection 
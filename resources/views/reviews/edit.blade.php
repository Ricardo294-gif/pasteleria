@extends('layouts.layout')

@section('title', 'Editar valoración - ' . $producto->nombre)

@section('content')
    <section class="review-form-page section">
        <div class="container">
            <a href="{{ route('producto.detalle', $producto->id) }}" class="back-link mb-4 d-inline-block">
                <i class="bi bi-arrow-left"></i> Volver al producto
            </a>

            <div class="section-title text-center">
                <h2>Editar tu valoración de {{ $producto->nombre }}</h2>
                <p>Actualiza tu opinión sobre este producto</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="review-form-card">
                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" id="reviewForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-4 text-center">
                                <label for="rating" class="form-label">Tu valoración</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" {{ $review->rating == 5 ? 'checked' : '' }} required />
                                    <label for="star5" title="5 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4" {{ $review->rating == 4 ? 'checked' : '' }} />
                                    <label for="star4" title="4 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3" {{ $review->rating == 3 ? 'checked' : '' }} />
                                    <label for="star3" title="3 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2" {{ $review->rating == 2 ? 'checked' : '' }} />
                                    <label for="star2" title="2 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1" {{ $review->rating == 1 ? 'checked' : '' }} />
                                    <label for="star1" title="1 estrella"><i class="bi bi-star-fill"></i></label>
                                </div>
                                @error('rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label">Tu comentario (opcional)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Describe tu experiencia con este producto...">{{ old('comment', $review->comment) }}</textarea>
                                <div class="form-text">Máximo 500 caracteres</div>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-save me-1"></i> Guardar cambios
                                </button>
                                
                                <button type="button" onclick="eliminarResena()" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Eliminar reseña
                                </button>
                            </div>
                        </form>
                        
                        <!-- Formulario separado para eliminar la reseña -->
                        <form id="delete-form" action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display: none;" onsubmit="return false;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect_url" value="{{ route('producto.detalle', $producto->id) }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .review-form-page {
            padding: 60px 0;
        }

        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .review-form-card {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* Estilos para Dark Mode */
        [data-theme="dark"] .review-form-card {
            background: var(--surface-color);
            box-shadow: var(--shadow);
            color: var(--text-color);
        }

        [data-theme="dark"] .form-control {
            background-color: var(--bg-color);
            border-color: var(--border-color);
            color: var(--text-color);
        }

        /* Estilo para las estrellas */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            font-size: 1.5rem;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            color: #ddd;
            margin: 0 3px;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }

        /* Botones de acción */
        .btn-primary {
            background-color: #ff7070;
            border-color: #ff7070;
        }

        .btn-primary:hover {
            background-color: #e56464;
            border-color: #e56464;
        }

        .btn-danger {
            color: #dc3545;
            background-color: transparent;
            border: 1px solid #dc3545;
        }

        .btn-danger:hover {
            color: #fff;
            background-color: #dc3545;
        }
        
        /* Modal personalizado */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .custom-modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .custom-modal-icon {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        .custom-modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .custom-modal-text {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #666;
        }
        
        .custom-modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .modal-btn {
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }
        
        .modal-btn-cancel {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .modal-btn-cancel:hover {
            background-color: #e9ecef;
        }
        
        .modal-btn-confirm {
            background-color: #dc3545;
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background-color: #c82333;
        }
    </style>
    
    <!-- Modal personalizado para confirmar eliminación -->
    <div class="custom-modal" id="deleteConfirmModal">
        <div class="custom-modal-content">
            <div class="custom-modal-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="custom-modal-title">Confirmar eliminación</div>
            <div class="custom-modal-text">¿Estás seguro de que deseas eliminar esta reseña? Esta acción no se puede deshacer.</div>
            <div class="custom-modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">Cancelar</button>
                <button class="modal-btn modal-btn-confirm" onclick="confirmDelete()">Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        // Funciones para el modal personalizado
        function eliminarResena() {
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }
        
        window.closeDeleteModal = function() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
        };
        
        function confirmDelete() {
            // Cerrar el modal primero
            closeDeleteModal();
            
            // Desactivar temporalmente preventDefault
            if (window.customAlerts && window.customAlerts.disablePreventDefault) {
                window.customAlerts.disablePreventDefault();
            }
            
            // Obtener el formulario
            const form = document.getElementById('delete-form');
            
            // Eliminar cualquier handler que pudiera existir
            form.onsubmit = null;
            
            // Deshabilitar el botón del formulario para evitar clics múltiples
            const submitBtn = document.querySelector('.modal-btn-confirm');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Eliminando...';
            }
            
            // Enviar el formulario sin más confirmaciones
            setTimeout(function() {
                try {
                    form.submit();
                } catch (e) {
                    console.error("Error al enviar el formulario:", e);
                    // Si falla el envío del formulario, redirigir manualmente
                    window.location.href = '{{ route('producto.detalle', $producto->id) }}';
                }
            }, 100);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const starInputs = document.querySelectorAll('.star-rating input');
            const starLabels = document.querySelectorAll('.star-rating label');

            // Manejar el clic en las estrellas
            starLabels.forEach(label => {
                label.addEventListener('click', function() {
                    const forAttr = this.getAttribute('for');
                    const starValue = document.getElementById(forAttr).value;
                    console.log('Valoración seleccionada:', starValue);
                });
            });

            // Validación del formulario
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    // Verificamos que exista al menos una estrella seleccionada
                    const ratingSelected = Array.from(starInputs).some(input => input.checked);
                    if (!ratingSelected) {
                        e.preventDefault();
                        alert('Por favor, selecciona una valoración de 1 a 5 estrellas.');
                    }
                });
            }
            
            // Cerrar modal al hacer clic fuera
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('deleteConfirmModal');
                if (event.target === modal) {
                    closeDeleteModal();
                }
            });
        });
    </script>
@endsection 
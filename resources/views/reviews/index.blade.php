@extends('layouts.layout')

@section('title', 'Opiniones sobre ' . $producto->nombre)

@section('content')
    <section class="reviews-page section">
        <div class="container">
            <a href="{{ route('producto.detalle', $producto->id) }}" class="back-link mb-4 d-inline-block">
                <i class="bi bi-arrow-left"></i> Volver al producto
            </a>

            <div class="section-title text-center">
                <h2>Opiniones sobre {{ $producto->nombre }}</h2>
                <p>Valoración promedio:
                    <span class="rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($producto->getAverageRatingAttribute()))
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                        <span class="rating-value">({{ number_format($producto->getAverageRatingAttribute(), 1) }})</span>
                    </span>
                </p>
                <p>{{ $producto->getReviewsCountAttribute() }} opiniones en total</p>
            </div>

            <div class="reviews-container">
                @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div class="review-card" data-aos="fade-up">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <h4>{{ $review->user->name }} {{ $review->user->apellido }}</h4>
                                    <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="review-content">
                                <p>{{ $review->comment ?: 'Sin comentario.' }}</p>
                            </div>
                            @if(Auth::check() && (Auth::id() == $review->user_id || Auth::user()->is_admin))
                                <div class="review-actions">
                                    @if(Auth::id() == $review->user_id)
                                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    @endif
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="delete-review-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-review" data-review-id="{{ $review->id }}">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="pagination-container">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="no-reviews">
                        <p>Este producto aún no tiene opiniones.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

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
                <button class="modal-btn modal-btn-confirm" id="btnConfirmDeleteReview">Eliminar</button>
            </div>
        </div>
    </div>

    <style>
        .reviews-page {
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

        .rating {
            color: #ffc107;
            font-size: 1.2rem;
            display: inline-block;
            margin: 10px 0;
        }

        .rating-value {
            color: #666;
            margin-left: 5px;
        }

        .reviews-container {
            margin-top: 40px;
        }

        .review-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .reviewer-info h4 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--heading-color);
        }

        .review-date {
            font-size: 0.9rem;
            color: #888;
        }

        .review-rating {
            color: #ffc107;
        }

        .review-content {
            margin-bottom: 15px;
            color: #555;
        }

        .review-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .review-actions .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
        }

        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
            color: white;
        }

        .no-reviews {
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar todos los botones de eliminar reseña
            const deleteButtons = document.querySelectorAll('.btn-delete-review');
            const modal = document.getElementById('deleteConfirmModal');
            const confirmBtn = document.getElementById('btnConfirmDeleteReview');
            let currentForm = null;
            
            // Función para mostrar el modal
            function showDeleteModal(form) {
                currentForm = form;
                modal.style.display = 'flex';
            }
            
            // Función para cerrar el modal
            window.closeDeleteModal = function() {
                modal.style.display = 'none';
                currentForm = null;
            };
            
            // Cerrar modal al hacer clic fuera
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeDeleteModal();
                }
            });
            
            // Añadir evento al botón de cerrar modal
            document.querySelector('.modal-btn-cancel').addEventListener('click', closeDeleteModal);
            
            // Manejar confirmación de eliminación
            confirmBtn.addEventListener('click', function() {
                if (currentForm) {
                    // Desactivar temporalmente preventDefault
                    if (window.customAlerts && window.customAlerts.disablePreventDefault) {
                        window.customAlerts.disablePreventDefault();
                    }
                    
                    // Eliminar cualquier handler que pudiera existir
                    currentForm.onsubmit = null;
                    
                    // Deshabilitar el botón para evitar múltiples envíos
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Eliminando...';
                    
                    // Usar setTimeout para evitar comportamientos inesperados
                    setTimeout(function() {
                        try {
                            // Enviar el formulario sin más confirmaciones
                            currentForm.submit();
                        } catch (e) {
                            console.error("Error al enviar el formulario:", e);
                            // Si falla, intentar redirigir
                            window.location.reload();
                        }
                    }, 100);
                }
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Prevenir cualquier comportamiento predeterminado
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Obtener el formulario asociado al botón
                    const form = this.closest('form');
                    
                    // Eliminar cualquier handler que pudiera existir
                    form.onsubmit = null;
                    
                    // Mostrar el modal de confirmación personalizado
                    showDeleteModal(form);
                });
            });
        });
    </script>
@endsection

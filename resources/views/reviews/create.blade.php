@extends('layouts.layout')

@section('title', 'Valorar ' . $producto->nombre)

@section('content')
    <section class="review-form-page section">
        <div class="container">
            <a href="{{ route('producto.detalle', $producto->id) }}" class="back-link mb-4 d-inline-block">
                <i class="bi bi-arrow-left"></i> Volver al producto
            </a>

            <div class="section-title text-center">
                <h2>Valorar {{ $producto->nombre }}</h2>
                <p>Comparte tu opinión sobre este producto</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="review-form-card">
                        <form action="{{ route('reviews.store', $producto->id) }}" method="POST" id="reviewForm">
                            @csrf

                            <div class="mb-4 text-center">
                                <label for="rating" class="form-label">Tu valoración</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" required />
                                    <label for="star5" title="5 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4" title="4 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3" title="3 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2" title="2 estrellas"><i class="bi bi-star-fill"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1" title="1 estrella"><i class="bi bi-star-fill"></i></label>
                                </div>
                                @error('rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label">Tu comentario (opcional)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Describe tu experiencia con este producto...">{{ old('comment') }}</textarea>
                                <div class="form-text">Máximo 500 caracteres</div>
                                @error('comment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Publicar opinión</button>
                            </div>
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
    </style>

    <script>
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
            reviewForm.addEventListener('submit', function(e) {
                const ratingSelected = Array.from(starInputs).some(input => input.checked);
                if (!ratingSelected) {
                    e.preventDefault();
                    alert('Por favor, selecciona una valoración de 1 a 5 estrellas.');
                }
            });
        });
    </script>
@endsection 
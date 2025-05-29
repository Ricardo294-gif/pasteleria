@extends('layouts.layout')

@section('title', $producto->nombre)

@section('content')
    <!-- Botón para volver atrás -->
    <div class="container mt-4">
        <a href="{{ route('index') }}" style="color: #ff7070; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease;">
            <i class="bi bi-arrow-left" style="margin-right: 5px;"></i> Volver
        </a>
    </div>

    <section class="product-detail section" style="background-color: #fef6e6;">
        <div class="container">
            <div class="row">
                <!-- Imagen del producto -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="product-image" style="border-radius: 6px; overflow: hidden; padding: 0;">
                        <img src="{{ asset(file_exists(public_path('img/productos/' . $producto->imagen)) ? 'img/productos/' . $producto->imagen : (file_exists(public_path('storage/' . $producto->imagen)) ? 'storage/' . $producto->imagen : 'img/productos/default.jpg')) }}" alt="{{ $producto->nombre }}" class="img-fluid">
                    </div>
                </div>

                <!-- Información del producto -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="product-info">
                        <h1 class="product-title" style="font-family: 'Dancing Script', cursive; font-size: 2.8rem; text-decoration: underline; text-decoration-thickness: 2px; text-underline-offset: 13px; font-style: normal; font-weight: 900; margin-bottom: 30px; margin-top: 30px;">{{ $producto->nombre }}</h1>
                        
                        <p class="mb-5" style="color: #666666; font-size: 1.2rem;">{{ $producto->descripcion }}</p>

                        <div class="d-flex align-items-center mb-5" style="margin-top: -30px;">
                            <span style="font-size: 1.7rem; font-weight: 400; color: #666666; margin-right: 10px;">Desde:</span>
                            <span id="product-price" class="price" style="font-size: 2.8rem; color: #ff7070; font-weight: bold;">{{ number_format($producto->precio, 2) }}€</span>
                        </div>

                        <!-- Personalización del producto - Movido arriba -->
                        <div class="size-options d-flex justify-content-start gap-2 mb-4" style="margin-top: -27px;">
                                        <div class="size-option">
                                <input type="radio" name="product-size" id="size-normal" value="normal" data-price="{{ $producto->precio }}" checked class="d-none">
                                <label for="size-normal" class="size-label" style="background-color: #ff7070; border: 1px solid #ff7070; color: white; padding: 4px 20px; border-radius: 16px; font-size: 1.5rem; font-family: 'Dancing Script', cursive; display: inline-block; text-align: center; min-width: 90px; font-weight: 800; box-shadow: 0 4px 8px rgba(255, 112, 112, 0.3); transition: all 0.3s ease;">
                                    Normal
                                            </label>
                                        </div>
                                        <div class="size-option">
                                <input type="radio" name="product-size" id="size-grande" value="grande" data-price="{{ number_format($producto->precio * 1.3, 2) }}" class="d-none">
                                <label for="size-grande" class="size-label" style="background-color: #fff4e5; border: 1px solid #ff7070; color: #ff7070; padding: 4px 20px; border-radius: 16px; font-size: 1.5rem; font-family: 'Dancing Script', cursive; display: inline-block; text-align: center; min-width: 90px; font-weight: 800; box-shadow: 0 4px 8px rgba(255, 112, 112, 0.2); transition: all 0.3s ease;">
                                    Grande
                                            </label>
                                        </div>
                                        <div class="size-option">
                                <input type="radio" name="product-size" id="size-muygrande" value="muygrande" data-price="{{ number_format($producto->precio * 1.5, 2) }}" class="d-none">
                                <label for="size-muygrande" class="size-label" style="background-color: #fff4e5; border: 1px solid #ff7070; color: #ff7070; padding: 4px 20px; border-radius: 16px; font-size: 1.5rem; font-family: 'Dancing Script', cursive; display: inline-block; text-align: center; min-width: 90px; font-weight: 800; box-shadow: 0 4px 8px rgba(255, 112, 112, 0.2); transition: all 0.3s ease;">
                                    Muy grande
                                            </label>
                                        </div>
                                    </div>

                        <div style="border-radius: 10px; padding: 20px; margin-bottom: -20px; margin-top: 30px;">
                            <!-- Selector de cantidad y estrellas -->
                            <div class="d-flex align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn quantity-btn" style="background-color: #ff7070; border: none; color: white; height: 40px; width: 40px; border-radius: 19px; display: flex; align-items: center; justify-content: center;" onclick="decrementarCantidad()">
                                        <i class="bi bi-dash" style="font-size: 1.5rem; font-weight: bold;"></i>
                                    </button>
                                    <span id="cantidad-display" class="mx-4" style="font-size: 1.5rem; min-width: 15px; text-align: center; font-weight: 700;">1</span>
                                    <button type="button" class="btn quantity-btn" style="background-color: #ff7070; border: none; color: white; height: 40px; width: 40px; border-radius: 19px; display: flex; align-items: center; justify-content: center;" onclick="incrementarCantidad()">
                                        <i class="bi bi-plus" style="font-size: 1.5rem; font-weight: bold;"></i>
                                    </button>
                                </div>
                                
                                <div class="ms-3">
                                    <div class="rating-container">
                                    <span class="text-warning" style="font-size: 1.2rem; padding: 5px 10px; border-radius: 16px; display: inline-block;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($producto->getAverageRatingAttribute()))
                                        <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                        <span class="text-muted" style="font-weight: 600; color: #666666; font-size: 0.85rem;">({{ $producto->getReviewsCountAttribute() }} opiniones)</span>
                                    </div>
                        </div>
                            </div>
                            </div>

                        <!-- Formulario de compra -->
                        <form action="{{ route('carrito.agregar') }}" method="POST" class="product-form add-to-cart-form" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <input type="hidden" name="tamano" id="tamano" value="normal">
                            <input type="hidden" name="cantidad" id="cantidad" value="1">

                            <div class="product-actions d-flex gap-3">
                                <button type="submit" class="btn action-btn flex-grow-1" style="background-color: #ff7070; color: white; border: none; padding: 15px; border-radius: 19px; font-family: 'Dancing Script', cursive; font-size: 1.3rem; font-weight: 700;">
                                    <i class="bi bi-cart3 me-2"></i> Añadir al carrito
                                </button>
                                <a href="javascript:void(0)" onclick="comprarAhora()" class="btn action-btn flex-grow-1" style="background-color: white; color: #ff7070; border: 1px solid #ff7070; padding: 15px; border-radius: 19px; font-family: 'Dancing Script', cursive; font-size: 1.3rem; font-weight: 700;">
                                    <i class="bi bi-bag me-2"></i> Comprar ahora
                                </a>
                            </div>
                        </form>

                        <!-- Formulario separado para compra directa -->
                        <form id="compra-directa-form" action="{{ route('carrito.compra.directa') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <input type="hidden" name="cantidad" id="cantidad-compra-directa" value="1">
                            <input type="hidden" name="tamano" id="tamano-compra-directa" value="normal">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Secciones de descripción e ingredientes -->
    <section class="product-details-section" style="background-color: #fef6e6; padding-top: 0;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div style="border-top: 1px solid #e0e0e0; padding-top: 30px; padding-bottom: 30px; margin-bottom: -60px; border-bottom: 1px solid #e0e0e0;">
                        <h3 style="font-family: 'Dancing Script', cursive; color: #ff7070; font-size: 1.8rem; font-weight: 700;">Descripción</h3>
                        <p style="color: #666666;">
                            @if(empty($producto->descripcion_larga))
                                Delicioso producto artesanal elaborado con los mejores ingredientes. Disfruta de una experiencia gastronómica única con nuestros exclusivos sabores que harán las delicias de todos los comensales. Ideal para ocasiones especiales o para darte un capricho cualquier día de la semana. Nuestros maestros artesanos han perfeccionado la receta para ofrecerte un producto de la máxima calidad.
                            @else
                                {{ $producto->descripcion_larga }}
                            @endif
                            <!-- ID: {{ $producto->id }} - Timestamp: {{ time() }} -->
                        </p>
                        
                        <h3 style="font-family: 'Dancing Script', cursive; color: #ff7070; font-size: 1.8rem; margin-top: 30px; font-weight: 700;">Ingredientes</h3>
                        <p style="color: #666666;">{{ $producto->ingredientes }}</p>
                            </div>
                            </div>
            </div>
        </div>
    </section>

    <!-- Sección de productos relacionados -->
    <section class="related-products-section" style="background-color: #fef6e6; padding: 60px 0; margin-top: 50px;">
        <div class="container">
            <div class="review-header" style="position: relative; margin-bottom: 40px; text-align: center;">
                <div style="position: absolute; width: 100%; height: 1px; background: linear-gradient(to right, transparent, #ff7070, transparent); top: 50%; z-index: 1;"></div>
                <h2 style="display: inline-block; position: relative; z-index: 2; background-color: #fef6e6; padding: 0 30px; font-family: 'Dancing Script', cursive; color: #ff7070; font-size: 2.5rem; font-weight: 700; margin: 0;">También te puede gustar</h2>
            </div>
            
            <div class="row justify-content-center">
                @foreach($productosRelacionados as $productoRelacionado)
                    <div class="col-lg-3 col-md-4 col-6 mb-4">
                        <div style="background: #FFF4E5; border-radius: 15px; overflow: hidden; border: 2px solid #ff7070; box-shadow: 0 5px 15px rgba(255, 112, 112, 0.1); transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column;">
                            <div style="padding: 20px 15px 10px; text-align: center;">
                                <h3 style="font-size: 1.3rem; margin-bottom: 15px; font-family: 'Dancing Script', cursive; font-weight: 700; display: inline-block; position: relative;">
                                    {{ $productoRelacionado->nombre }}
                                    <span style="position: absolute; bottom: -4px; left: 0; width: 100%; height: 1px; background:rgb(0, 0, 0);"></span>
                                </h3>
                                
                                <div style="width: 150px; height: 150px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; background: #222;">
                                    <img src="{{ asset(file_exists(public_path('img/productos/' . $productoRelacionado->imagen)) ? 'img/productos/' . $productoRelacionado->imagen : (file_exists(public_path('storage/' . $productoRelacionado->imagen)) ? 'storage/' . $productoRelacionado->imagen : 'img/productos/default.jpg')) }}" alt="{{ $productoRelacionado->nombre }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                
                                <div style="margin-bottom: 0px; margin-top: 10px;">
                                    <div id="desde" style="font-size: 0.9rem; color: #666; font-weight: 500;">Desde:</div>
                                    <div style="font-size: 1.8rem; color: #ff7070; font-weight: bold;">{{ number_format($productoRelacionado->precio, 2) }}€</div>
                                </div>
                            </div>
                            
                            <div style="margin-top: auto; padding: 0 15px 20px; text-align: center;">
                                <a href="{{ route('producto.detalle', $productoRelacionado->id) }}" class="btn" style="background-color: #ff7070; color: white; border: none; padding: 10px 0; border-radius: 10px; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye me-2"></i> Ver producto
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Sección de reseñas -->
    <section class="reviews-section" style="background-color: #fef6e6; padding: 60px 0;">
        <div class="container">
            <div class="review-header" style="position: relative; margin-bottom: 40px; text-align: center;">
                <div style="position: absolute; width: 100%; height: 1px; background: linear-gradient(to right, transparent, #ff7070, transparent); top: 50%; z-index: 1;"></div>
                <h2 style="display: inline-block; position: relative; z-index: 2; background-color: #fef6e6; padding: 0 30px; font-family: 'Dancing Script', cursive; color: #ff7070; font-size: 2.5rem; font-weight: 700; margin: 0;">Opiniones de clientes</h2>
            </div>

            <div class="row">
                
                <!-- Panel de filtros y reseñas -->
                <div class="col-12">
                    <div style="border-radius: 20px; padding: 25px 30px; position: relative;">
                        <div class="d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                            <h4 style="font-size: 1.3rem; color: #333; margin-bottom: 0; font-weight: 600; display: flex; align-items: center;">
                                <i class="bi bi-funnel-fill me-2" style="color: #ff7070;"></i> Filtrar opiniones
                            </h4>
                            
                            <div>
                                @auth
                                    <a href="{{ route('reviews.create', $producto->id) }}" class="btn" style="background: #ff7070; color: white; border: none; padding: 8px 15px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(255, 112, 112, 0.3); display: inline-flex; align-items: center;">
                                        <i class="bi bi-pencil-fill me-2"></i> Escribe tu opinión
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn" style="background: transparent; color: #ff7070; border: 2px solid #ff7070; padding: 8px 15px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease; display: inline-flex; align-items: center;">
                                        <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar sesión
                                    </a>
                                @endauth
                            </div>
                        </div>
                        
                        <div class="filter-buttons" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 25px;">
                            <button class="filter-btn active" data-rating="all" style="background: #ff7070; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 500; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                                Todas las estrellas
                            </button>
                            @for ($i = 5; $i >= 1; $i--)
                                <button class="filter-btn" data-rating="{{ $i }}" style="background: transparent; color: #666; border: 1px solid #ddd; padding: 10px 20px; border-radius: 10px; font-weight: 500; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="bi bi-star-fill me-1" style="color: #FFD700;"></i> {{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}
                                </button>
                            @endfor
                        </div>
                        
                        @if($producto->reviews->count() > 0)
                            <div class="reviews-container">
                                @foreach($producto->reviews->take(5) as $review)
                                    <div class="review-card" data-rating="{{ $review->rating }}" style="border: 1px solid #eee; border-radius: 10px; padding: 15px; margin-bottom: 15px; background-color: #fff;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                                            <div>
                                                <h5 style="font-size: 1.1rem; margin-bottom: 5px; color: #333;">{{ $review->user->name }} {{ $review->user->apellido ?? '' }}</h5>
                                                <div style="color: #FFD700; font-size: 0.9rem;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="bi bi-star-fill"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center;">
                                                <span style="color: #999; font-size: 0.8rem; margin-right: 10px;">{{ $review->created_at->format('d/m/Y') }}</span>
                                                @auth
                                                    @if(Auth::id() == $review->user_id)
                                                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm" style="background-color: #ff7070; color: white; border-radius: 10px; font-size: 0.75rem; padding: 3px 10px;">
                                                            <i class="bi bi-pencil-fill"></i> Editar
                                                        </a>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                        <p style="color: #555; font-size: 0.95rem; margin-bottom: 0;">{{ $review->comment ?? 'Sin comentario' }}</p>
                                    </div>
                                @endforeach
                                
                                @if($producto->reviews->count() > 5)
                                    <div class="load-more-container text-center mt-4">
                                        <button id="load-more-reviews" class="btn btn-outline-primary" style="padding: 10px 25px; border-radius: 30px; transition: all 0.3s ease; border-color: #ff7070; color: #ff7070;">
                                            <i class="bi bi-plus-circle me-2"></i> Ver más opiniones
                                        </button>
                                        <div class="loading-spinner mt-3" style="display: none;">
                                            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem; color: #ff7070 !important;">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                        <div class="no-reviews-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0; text-align: center;">
                            <div style="width: 120px; height: 120px; background: #f9f9f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                <i class="bi bi-chat-square-heart" style="font-size: 3.5rem; color: #ddd;"></i>
                                </div>
                            <h5 style="font-size: 1.3rem; color: #333; margin-bottom: 10px;">¡Sé el primero en opinar!</h5>
                            <p style="color: #777; font-size: 1rem; max-width: 80%; margin: 0 auto 5px;">Comparte tu experiencia con este producto y ayuda a otros clientes</p>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    // Funciones para incrementar y decrementar la cantidad
        function decrementarCantidad() {
        // Asegurar que el tamaño seleccionado está actualizado
        const tamanoSeleccionado = document.querySelector('input[name="product-size"]:checked').value;
        document.getElementById('tamano').value = tamanoSeleccionado;
        document.getElementById('tamano-compra-directa').value = tamanoSeleccionado;
        
        let cantidadInput = document.getElementById('cantidad');
        let cantidadDisplay = document.getElementById('cantidad-display');
        let cantidadCompraDirecta = document.getElementById('cantidad-compra-directa');
        let cantidad = parseInt(cantidadInput.value);
        
        if (cantidad > 1) {
            cantidad--;
            cantidadInput.value = cantidad;
            cantidadDisplay.textContent = cantidad;
            cantidadCompraDirecta.value = cantidad;
        }
    }
    
    function incrementarCantidad() {
        // Asegurar que el tamaño seleccionado está actualizado
        const tamanoSeleccionado = document.querySelector('input[name="product-size"]:checked').value;
        document.getElementById('tamano').value = tamanoSeleccionado;
        document.getElementById('tamano-compra-directa').value = tamanoSeleccionado;
        
        let cantidadInput = document.getElementById('cantidad');
        let cantidadDisplay = document.getElementById('cantidad-display');
        let cantidadCompraDirecta = document.getElementById('cantidad-compra-directa');
        let cantidad = parseInt(cantidadInput.value);
        
        if (cantidad < 50) {
            cantidad++;
            cantidadInput.value = cantidad;
            cantidadDisplay.textContent = cantidad;
            cantidadCompraDirecta.value = cantidad;
        }
    }
    
    // Función para actualizar el precio según el tamaño seleccionado
    function actualizarPrecio(precio) {
        document.getElementById('product-price').textContent = precio + '€';
    }
    
    // Actualizar el tamaño seleccionado y el precio
    document.querySelectorAll('input[name="product-size"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Actualizar campos ocultos con el tamaño seleccionado
            const tamanoSeleccionado = this.value;
            document.getElementById('tamano').value = tamanoSeleccionado;
            document.getElementById('tamano-compra-directa').value = tamanoSeleccionado;
            
            console.log('Tamaño seleccionado cambiado a: ' + tamanoSeleccionado);
            
            // Cambiar el estilo de los botones
            document.querySelectorAll('.size-label').forEach(function(label) {
                label.style.backgroundColor = '#fff4e5';
                label.style.color = '#ff7070';
            });
            
            // El botón seleccionado debe tener fondo rosa y texto blanco
            this.nextElementSibling.style.backgroundColor = '#ff7070';
            this.nextElementSibling.style.color = 'white';
            
            // Actualizar el precio según el tamaño seleccionado
            actualizarPrecio(this.getAttribute('data-price'));
        });
    });
    
    // Iniciar con el primer botón seleccionado
    document.getElementById('size-normal').nextElementSibling.style.backgroundColor = '#ff7070';
    document.getElementById('size-normal').nextElementSibling.style.color = 'white';
    
    // Función para comprar ahora
    function comprarAhora() {
        // Asegurar que el tamaño seleccionado esté actualizado antes de enviar
        let tamanoSeleccionado = document.querySelector('input[name="product-size"]:checked').value;
        document.getElementById('tamano-compra-directa').value = tamanoSeleccionado;
        console.log('Comprando ahora con tamaño: ' + tamanoSeleccionado);
        document.getElementById('compra-directa-form').submit();
    }
    
    // Asegurar que el tamaño seleccionado se capture correctamente antes de enviar el formulario
    document.addEventListener('DOMContentLoaded', function() {
        // Asegurarse de que los campos ocultos tengan el tamaño correcto al cargar
        const tamanoInicialSeleccionado = document.querySelector('input[name="product-size"]:checked').value;
        document.getElementById('tamano').value = tamanoInicialSeleccionado;
        document.getElementById('tamano-compra-directa').value = tamanoInicialSeleccionado;
        console.log('Tamaño inicial establecido a: ' + tamanoInicialSeleccionado);
        
        const addToCartForm = document.getElementById('add-to-cart-form');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                // Obtener el tamaño actualmente seleccionado
                let tamanoSeleccionado = document.querySelector('input[name="product-size"]:checked').value;
                // Actualizar el campo oculto con el tamaño seleccionado
                document.getElementById('tamano').value = tamanoSeleccionado;
                console.log('Enviando formulario con tamaño: ' + tamanoSeleccionado);
            });
        }
    });
    
    // Script para manejar los botones de filtro y carga incremental de reseñas
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración inicial
        const productoId = {{ $producto->id }};
        // Empezamos desde la página 2 porque la página 1 ya se muestra con Blade
        let paginaActual = 2;
        const reviewsPorPagina = 5;
        let cargando = false;
        let filtroActual = 'all';
        let hayMasReviews = {{ $producto->reviews->count() > 5 ? 'true' : 'false' }};
        
        // Manejar filtros
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // No hacer nada si ya está seleccionado este filtro
                if (this.classList.contains('active')) {
                    return;
                }
                
                // Remover clase activa de todos los botones
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.style.background = 'transparent';
                    btn.style.color = '#666';
                });
                
                // Agregar clase activa al botón seleccionado
                this.classList.add('active');
                this.style.background = '#ff7070';
                this.style.color = 'white';
                
                // Actualizar filtro actual
                filtroActual = this.getAttribute('data-rating');
                
                // Mostrar spinner y ocultar el botón si estaba visible
                const loadMoreBtn = document.getElementById('load-more-reviews');
                const loadingSpinner = document.querySelector('.loading-spinner');
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = 'none';
                }
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'block';
                }
                
                // Al filtrar, volvemos a la página 1 y limpiamos todo el contenedor
                paginaActual = 1;
                
                // Vaciar el contenedor de reseñas y conservar el botón de cargar más
                const reviewsContainer = document.querySelector('.reviews-container');
                const loadMoreContainer = document.querySelector('.load-more-container');
                
                if (reviewsContainer) {
                    if (loadMoreContainer) {
                        reviewsContainer.innerHTML = '';
                        reviewsContainer.appendChild(loadMoreContainer);
                    } else {
                        reviewsContainer.innerHTML = '';
                    }
                }
                
                // Cargar las reseñas con el nuevo filtro
                cargarResenas();
            });
        });
        
        // Función para cargar las reseñas
        function cargarResenas() {
            if (cargando) return;
            
            cargando = true;
            const loadMoreBtn = document.getElementById('load-more-reviews');
            const loadingSpinner = document.querySelector('.loading-spinner');
            
            // Mostrar spinner
            if (loadingSpinner) {
                loadingSpinner.style.display = 'block';
            }
            
            // Consultar API para obtener más reseñas
            fetch(`/api/producto/${productoId}/resenas?pagina=${paginaActual}&porPagina=${reviewsPorPagina}&rating=${filtroActual}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar las reseñas');
                    }
                    return response.json();
                })
                .then(data => {
                    cargando = false;
                    
                    // Ocultar spinner
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'none';
                    }
                    
                    // Actualizar si hay más reseñas
                    hayMasReviews = data.hay_mas;
                    
                    // Mostrar u ocultar el botón de cargar más según el resultado
                    if (loadMoreBtn) {
                        loadMoreBtn.style.display = hayMasReviews ? 'inline-block' : 'none';
                    }
                    
                    // Si no hay reseñas y es la primera página (filtrado)
                    if (data.resenas.length === 0 && paginaActual === 1) {
                        const reviewsContainer = document.querySelector('.reviews-container');
                        const noReviewsTemplate = document.querySelector('.no-reviews-state');
                        
                        if (reviewsContainer && noReviewsTemplate) {
                            // Crear clon del estado de no reseñas
                            const noReviewsElement = noReviewsTemplate.cloneNode(true);
                            noReviewsElement.style.display = 'flex';
                            
                            // Limpiar contenedor y mostrar mensaje de no hay reseñas
                            reviewsContainer.innerHTML = '';
                            reviewsContainer.appendChild(noReviewsElement);
                        }
                        return;
                    }
                    
                    // Renderizar las reseñas recibidas
                    renderizarResenas(data.resenas);
                    
                    // Incrementar la página para la próxima carga
                    paginaActual++;
                })
                .catch(error => {
                    console.error('Error:', error);
                    cargando = false;
                    
                    // Ocultar spinner
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'none';
                    }
                    
                    // Mostrar error
                    alert('Ha ocurrido un error al cargar las reseñas. Por favor, inténtalo de nuevo.');
                });
        }
        
        // Función para renderizar las reseñas
        function renderizarResenas(resenas) {
            const reviewsContainer = document.querySelector('.reviews-container');
            const loadMoreContainer = document.querySelector('.load-more-container');
            
            if (!reviewsContainer) return;
            
            resenas.forEach(review => {
                // Crear elemento de reseña
                const reviewElement = document.createElement('div');
                reviewElement.className = 'review-card';
                reviewElement.setAttribute('data-rating', review.rating);
                reviewElement.style.border = '1px solid #eee';
                reviewElement.style.borderRadius = '10px';
                reviewElement.style.padding = '15px';
                reviewElement.style.marginBottom = '15px';
                reviewElement.style.backgroundColor = '#fff';
                reviewElement.style.opacity = '0';
                reviewElement.style.transform = 'translateY(20px)';
                reviewElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                // Construir HTML de la reseña
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="bi bi-star${i <= review.rating ? '-fill' : ''}"></i>`;
                }
                
                // Botón de editar si el usuario es el autor
                let editButton = '';
                if (review.can_edit) {
                    editButton = `
                        <a href="${review.edit_url}" class="btn btn-sm" style="background-color: #ff7070; color: white; border-radius: 10px; font-size: 0.75rem; padding: 3px 10px;">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                    `;
                }
                
                reviewElement.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h5 style="font-size: 1.1rem; margin-bottom: 5px; color: #333;">${review.user.name} ${review.user.apellido || ''}</h5>
                            <div style="color: #FFD700; font-size: 0.9rem;">
                                ${stars}
                            </div>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <span style="color: #999; font-size: 0.8rem; margin-right: 10px;">${review.created_at}</span>
                            ${editButton}
                        </div>
                    </div>
                    <p style="color: #555; font-size: 0.95rem; margin-bottom: 0;">${review.comment || 'Sin comentario'}</p>
                `;
                
                // Insertar elemento antes del botón de cargar más
                if (loadMoreContainer) {
                    reviewsContainer.insertBefore(reviewElement, loadMoreContainer);
                } else {
                    reviewsContainer.appendChild(reviewElement);
                }
                
                // Animar entrada
                setTimeout(() => {
                    reviewElement.style.opacity = '1';
                    reviewElement.style.transform = 'translateY(0)';
                }, 50);
            });
        }
        
        // Evento para botón de cargar más
        const loadMoreBtn = document.getElementById('load-more-reviews');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                // No hacer nada si ya está cargando
                if (cargando) return;
                
                // Cargar más reseñas
                cargarResenas();
            });
        }
        
        // Implementar infinite scroll
        const loadMoreObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && hayMasReviews && !cargando) {
                    cargarResenas();
                }
            });
        }, {
            rootMargin: '100px 0px',
            threshold: 0.1
        });
        
        // Observar el botón de cargar más para implementar infinite scroll
        if (loadMoreBtn) {
            loadMoreObserver.observe(loadMoreBtn);
        }
    });

    window.onload = function() {
        // Inicializar la configuración de tamaño y precio
        document.getElementById('size-normal').nextElementSibling.style.backgroundColor = '#ff7070';
        document.getElementById('size-normal').nextElementSibling.style.color = 'white';
        document.getElementById('size-grande').nextElementSibling.style.backgroundColor = '#fff4e5';
        document.getElementById('size-grande').nextElementSibling.style.color = '#ff7070';
        document.getElementById('size-muygrande').nextElementSibling.style.backgroundColor = '#fff4e5';
        document.getElementById('size-muygrande').nextElementSibling.style.color = '#ff7070';
    };
    </script>

<style>
    /* Estilos mejorados para la página de producto */
    .product-detail {
        padding: 70px 0;
    }

    /* Estilos para las estrellas y opiniones */
    .rating-container {
        display: flex;
        align-items: center;
    }

    @media (max-width: 768px) {
        .rating-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .rating-container .text-warning {
            margin-bottom: 5px;
        }

        .rating-container .text-muted {
            padding-left: 5px;
        }
    }

    .product-image {
        border-radius: 15px !important;
        overflow: hidden;
        box-shadow: none;
        transition: all 0.5s ease;
    }
    
    .product-image:hover {
        transform: none;
        box-shadow: none;
    }

    .product-title {
        position: relative;
        display: inline-block;
        margin-bottom: 40px !important;
    }

    .product-title:after {
        display: none; /* Eliminar línea roja debajo del título */
    }

    .price {
        font-size: 3.8rem !important;
        background: linear-gradient(45deg, #ff7070, #ff9e9e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 10px rgba(255, 112, 112, 0.2);
    }

    .product-actions {
        margin-top: 20px;
    }

    .action-btn {
            position: relative;
            overflow: hidden;
        z-index: 1;
        transition: all 0.4s ease !important;
        }

    .action-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
        width: 0%;
            height: 100%;
        background: rgba(255, 255, 255, 0.2);
        z-index: -1;
        transition: width 0.4s ease;
    }

    .action-btn:hover:before {
        width: 100%;
    }

    .size-label {
        transform: scale(1);
        transition: all 0.3s ease !important;
    }

    .size-label:hover {
        transform: scale(1.05) translateY(-3px);
    }

    .quantity-btn {
        transform: scale(1);
        transition: all 0.3s ease !important;
    }

    .quantity-btn:hover {
        transform: scale(1.1);
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, rgba(255, 112, 112, 0.5), transparent);
        margin: 40px 0;
        border: none;
    }

    .product-details-section h3 {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
    }

    .product-details-section h3:after {
        content: "";
            position: absolute;
        width: 50px;
        height: 2px;
        background-color: #ff7070;
        bottom: 0;
        left: 0;
        border-radius: 10px;
    }

    .reviews-dashboard, .no-reviews, .product-card {
        border-radius: 15px !important;
        transition: all 0.4s ease;
    }

    .reviews-dashboard:hover, .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
    }

    .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

    .btn-filter {
        border-radius: 30px !important;
        transition: all 0.3s ease;
    }

    .btn-filter.active {
        font-weight: 600;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 112, 112, 0.3);
    }

    .empty-icon {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Animación mejorada para todos los botones */
    .btn, .size-label, .btn-filter, .btn-outline-primary {
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease !important;
    }
    
    .btn:hover, .size-label:hover, .btn-filter:hover, .btn-outline-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255, 112, 112, 0.4) !important;
    }
    
    /* Efecto al hacer clic más suave */
    .btn:active, .size-label:active, .btn-filter:active, .btn-outline-primary:active {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(255, 112, 112, 0.3) !important;
    }

    /* Efectos de hover para las tarjetas de productos relacionados */
    .product-card {
        position: relative;
            overflow: hidden;
        z-index: 1;
    }

    .product-card::before {
            content: '';
        position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
        background: linear-gradient(45deg, rgba(255, 112, 112, 0.05), rgba(255, 112, 112, 0.15));
                            opacity: 0;
        transition: opacity 0.5s ease;
        z-index: -1;
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-card:hover img {
        transform: scale(1.1);
    }

    .product-img img {
        transition: transform 0.5s ease;
    }
</style>
@endsection

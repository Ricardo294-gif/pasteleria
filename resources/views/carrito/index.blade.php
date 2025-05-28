@extends('layouts.layout')

@section('title', 'Mi Carrito de Compras')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="cart-page" style="background-color: #fef6e6; padding: 60px 0;">
        <div class="container">
            <!-- Botón de volver -->
            <div class="mb-4">
                <a href="{{ route('index') }}" style="color: #ff7070; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                    <i class="bi bi-arrow-left" style="margin-right: 5px;"></i> Volver
                </a>
            </div>
            
            @if(count($items) > 0)
                <div class="text-center mb-5">
                    <h2 style="font-family: 'Dancing Script', cursive; color: #ff7070; font-size: 2.5rem; font-weight: 700;">Mi Carrito de Compras</h2>
                    @auth
                    <p style="color: #666;">Bienvenido, {{ Auth::user()->name }} - Revisa tus productos seleccionados</p>
                    @else
                    <p style="color: #666;">Revisa tus productos seleccionados</p>
                    @endauth
                </div>

                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            window.customAlerts.show('success', '{{ session('success') }}');
                        });
                    </script>
                @endif

                <div class="row g-4">
                    <div class="col-lg-8">
                        <!-- Items del carrito -->
                            @foreach($items as $item)
                            <div class="cart-item" style="background: white; border-radius: 6px; padding: 20px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                                    <div class="row align-items-center">
                                    <!-- Imagen del producto -->
                                    <div class="col-md-2">
                                        <div style="width: 80px; height: 80px; border-radius: 6px; overflow: hidden;">
                                            @if(is_object($item['producto']))
                                                <img src="{{ asset('img/productos/' . $item['producto']->imagen) }}"
                                                     alt="{{ $item['producto']->nombre }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('img/productos/' . $item['producto']['imagen']) }}"
                                                     alt="{{ $item['producto']['nombre'] }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Detalles del producto -->
                                    <div class="col-md-4">
                                            @if(is_object($item['producto']))
                                            <h4 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $item['producto']->nombre }}</h4>
                                            @else
                                            <h4 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $item['producto']['nombre'] }}</h4>
                                        @endif
                                        
                                        <div style="margin-bottom: 8px; display: flex; align-items: center;">
                                            <span style="font-size: 1.1rem; font-weight: 700; color: #ff7070;">€{{ number_format($item['precio_unitario'], 2) }}</span>
                                            @if(isset($item['tamano']) && $item['tamano'] != 'normal')
                                                <span style="font-size: 0.8rem; color: #888; font-style: italic; margin-left: 5px;">(precio ajustado)</span>
                                            @endif
                                        </div>

                                        <div style="display: inline-block; padding: 3px 10px; background-color: #f2f2f2; border-radius: 4px; font-size: 0.85rem; color: #666;">
                                            <span>Tamaño: </span>
                                            <span style="font-weight: 600;">
                                                    @if(isset($item['tamano']))
                                                        @if($item['tamano'] == 'normal')
                                                            Normal
                                                        @elseif($item['tamano'] == 'grande')
                                                            Grande
                                                        @elseif($item['tamano'] == 'muygrande')
                                                            Muy Grande
                                                        @else
                                                            {{ ucfirst($item['tamano']) }}
                                                        @endif
                                                    @else
                                                        Normal
                                                    @endif
                                                </span>
                                        </div>
                                            </div>

                                    <!-- Controles de cantidad -->
                                    <div class="col-md-3">
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <div style="display: flex; align-items: center; background-color: #f5f5f5; border-radius: 4px; padding: 5px 10px;">
                                                <button class="btn-decrementar" data-id="{{ $item['id'] }}" style="width: 30px; height: 30px; border-radius: 50%; border: none; background-color: white; color: #666; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                <span class="cantidad-producto" style="padding: 0 15px; font-weight: 600; color: #333; min-width: 40px; text-align: center;">{{ $item['cantidad'] }}</span>
                                                <button class="btn-incrementar" data-id="{{ $item['id'] }}" style="width: 30px; height: 30px; border-radius: 50%; border: none; background-color: white; color: #666; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Subtotal y eliminar -->
                                        <div class="col-md-3 text-end">
                                        <div style="margin-bottom: 10px; font-size: 1.2rem; font-weight: 700; color: #333;">
                                            €<span class="subtotal-producto">{{ number_format($item['subtotal'], 2) }}</span>
                                        </div>
                                        <button type="button" class="btn-eliminar" data-id="{{ $item['id'] }}" style="background-color: #ff7070; color: white; border: none; border-radius: 10px; padding: 5px 15px; font-size: 0.9rem; transition: all 0.3s ease;">
                                            <i class="bi bi-trash me-1"></i> Eliminar
                                                </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>

                    <!-- Resumen del pedido -->
                    <div class="col-lg-4">
                        <div style="background: white; border-radius: 6px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); position: sticky; top: 20px;">
                            <h3 style="font-family: 'Dancing Script', cursive; color: #333; font-size: 1.8rem; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">Resumen del Pedido</h3>
                            
                            <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px;">
                                <span style="font-size: 1.2rem; font-weight: 600; color: #666;">Total:</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #ff7070;">€{{ number_format($total, 2) }}</span>
                            </div>
                            
                            <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 15px;">
                                @auth
                                <a href="{{ route('compra') }}" id="checkoutButton" style="display: block; background-color: #ff7070; color: white; border: none; border-radius: 10px; padding: 12px; text-align: center; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(255, 112, 112, 0.2);">
                                    <i class="bi bi-cart-check me-2"></i> Proceder al Pago
                                </a>
                                @else
                                <a href="{{ route('login') }}" style="display: block; background-color: #ff7070; color: white; border: none; border-radius: 4px; padding: 12px; text-align: center; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(255, 112, 112, 0.2);" onclick="event.preventDefault(); document.getElementById('redirect-form').submit();">
                                    <i class="bi bi-cart-check me-2"></i> Iniciar sesión para pagar
                                </a>
                                <form id="redirect-form" action="{{ route('login') }}" method="GET" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="redirect_to_cart" value="1">
                                </form>
                                @endauth
                                
                                <form action="{{ route('carrito.vaciar') }}" method="POST" id="vaciar-carrito-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" id="btn-vaciar-carrito" style="display: block; width: 100%; background-color: transparent; color: #888; border: 1px solid #ddd; border-radius: 10px; padding: 10px; text-align: center; font-weight: 500; transition: all 0.3s ease; cursor: pointer;">
                                        <i class="bi bi-trash me-2"></i> Vaciar Carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div style="border-radius: 6px; padding: 60px 20px; text-align: center; margin: 40px auto; max-width: 500px;">
                    <div style="font-size: 5rem; color: #ff7070; margin-bottom: 20px;">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <h2 style="font-family: 'Dancing Script', cursive; color: #333; margin-bottom: 10px; font-size: 2rem;">Tu carrito está vacío</h2>
                    <p style="color: #888; margin-bottom: 30px;">Aún no has añadido productos a tu carrito</p>
                    <a href="{{ route('index') }}" style="display: inline-block; background-color: #ff7070; color: white; border-radius: 10px; padding: 12px 25px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(255, 112, 112, 0.2);">
                        <i class="bi bi-shop me-2"></i> Ir al menú de productos
                        </a>
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar incremento y decremento de productos
            const botonesDecrementar = document.querySelectorAll('.btn-decrementar');
            const botonesIncrementar = document.querySelectorAll('.btn-incrementar');
            const botonesEliminar = document.querySelectorAll('.btn-eliminar');
            
            botonesDecrementar.forEach(boton => {
                boton.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    actualizarCantidad(itemId, 'decrementar');
                });
            });
            
            botonesIncrementar.forEach(boton => {
                boton.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    actualizarCantidad(itemId, 'incrementar');
                });
            });
            
            botonesEliminar.forEach(boton => {
                boton.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    eliminarProducto(itemId);
                });
            });
            
            // Botón vaciar carrito
            const btnVaciarCarrito = document.getElementById('btn-vaciar-carrito');
            if (btnVaciarCarrito) {
                btnVaciarCarrito.addEventListener('click', function() {
                    mostrarConfirmacion();
                });
            }
            
            // Función para mostrar confirmación personalizada
            function mostrarConfirmacion() {
                // Crear el fondo oscuro
                const overlay = document.createElement('div');
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                overlay.style.zIndex = '1000';
                overlay.style.display = 'flex';
                overlay.style.justifyContent = 'center';
                overlay.style.alignItems = 'center';
                overlay.className = 'cart-confirmation-overlay';
                
                // Crear el modal
                const modal = document.createElement('div');
                modal.style.backgroundColor = 'white';
                modal.style.padding = '25px';
                modal.style.borderRadius = '10px';
                modal.style.maxWidth = '400px';
                modal.style.width = '90%';
                modal.style.textAlign = 'center';
                modal.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                modal.className = 'cart-confirmation-modal';
                
                // Contenido del modal
                modal.innerHTML = `
                    <div style="color: #ff7070; font-size: 3rem; margin-bottom: 15px;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <h3 style="font-family: 'Dancing Script', cursive; color: #333; margin-bottom: 15px; font-size: 1.8rem;" class="cart-confirmation-title">
                        ¿Estás seguro?
                    </h3>
                    <p style="color: #666; margin-bottom: 25px;" class="cart-confirmation-message">
                        ¿Realmente deseas vaciar todo el carrito de compras?
                    </p>
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <button id="cancelar-vaciar" style="padding: 8px 20px; background: transparent; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; color: #666; font-weight: 600;" class="cart-confirmation-cancel">
                            Cancelar
                        </button>
                        <button id="confirmar-vaciar" style="padding: 8px 20px; background: #ff7070; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;" class="cart-confirmation-confirm">
                            Sí, vaciar carrito
                        </button>
                    </div>
                `;
                
                // Añadir el modal al overlay
                overlay.appendChild(modal);
                
                // Añadir el overlay al body
                document.body.appendChild(overlay);
                
                // Manejar el clic en cancelar
                document.getElementById('cancelar-vaciar').addEventListener('click', function() {
                    document.body.removeChild(overlay);
                });
                
                // Manejar el clic en confirmar
                document.getElementById('confirmar-vaciar').addEventListener('click', function() {
                    document.getElementById('vaciar-carrito-form').submit();
                });
            }
            
            function eliminarProducto(itemId) {
                const url = `/carrito/quitar/${itemId}`;
                
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Eliminar el elemento del DOM
                        const itemContainer = document.querySelector(`button[data-id="${itemId}"]`).closest('.cart-item');
                        itemContainer.remove();
                        
                        // Comprobar si el carrito está vacío
                        const carritoItems = document.querySelectorAll('.cart-item');
                        if (carritoItems.length === 0) {
                            window.location.reload(); // Recargar para mostrar el estado vacío
                        } else {
                            // Actualizar el total (aquí podríamos recalcular sin recargar)
                            window.location.reload();
                        }
                        
                        // Mostrar mensaje de éxito
                        if (window.customAlerts) {
                            window.customAlerts.show('success', data.message || 'Producto eliminado correctamente');
                        }
                    } else {
                        // Mostrar mensaje de error
                        if (window.customAlerts) {
                            window.customAlerts.show('error', data.message);
                        } else {
                            alert(data.message || 'Error al eliminar el producto');
        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            
            function actualizarCantidad(itemId, accion) {
                const url = accion === 'incrementar' 
                    ? `/carrito/incrementar/${itemId}`
                    : `/carrito/decrementar/${itemId}`;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.eliminado) {
                            window.location.reload();
                        } else {
                            // Actualizar la cantidad y el subtotal en el DOM
                            const itemContainer = document.querySelector(`button[data-id="${itemId}"]`).closest('.cart-item');
                            itemContainer.querySelector('.cantidad-producto').textContent = data.cantidad;
                            itemContainer.querySelector('.subtotal-producto').textContent = parseFloat(data.subtotal).toFixed(2);
                            
                            // Actualizar el total del carrito
                            actualizarTotalCarrito();
                        }
                    } else {
                        // Mostrar mensaje de error
                        if (window.customAlerts) {
                            window.customAlerts.show('error', data.message);
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            
            // Función para calcular y actualizar el total del carrito
            function actualizarTotalCarrito() {
                const subtotales = document.querySelectorAll('.subtotal-producto');
                let nuevoTotal = 0;
                
                subtotales.forEach(subtotal => {
                    nuevoTotal += parseFloat(subtotal.textContent);
                });
                
                // Actualizar el total mostrado
                const totalElement = document.querySelector('.col-lg-4 span[style*="font-size: 1.5rem"]');
                if (totalElement) {
                    totalElement.textContent = '€' + nuevoTotal.toFixed(2);
                }
            }
            
            // Botón Proceder al Pago - prevenir múltiples clics
            const checkoutButton = document.getElementById('checkoutButton');
            if (checkoutButton) {
                checkoutButton.addEventListener('click', function(e) {
                    // Verificar si el botón ya fue pulsado
                    if (this.getAttribute('data-processing') === 'true') {
                        e.preventDefault();
                        return false;
                    }

                    // Marcar el botón como procesando
                    this.setAttribute('data-processing', 'true');
                    const textoOriginal = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';
                    this.style.pointerEvents = 'none';
                    this.style.opacity = '0.7';

                    // Permitir que continúe la navegación
                    return true;
                });
            }
        });
    </script>
@endsection

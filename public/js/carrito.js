document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('/carrito/count')
            .then(response => response.json())
            .then(data => {
                // Seleccionar todos los elementos con la clase cart-count para actualizar todos los contadores
                const contadores = document.querySelectorAll('.cart-count');
                if (contadores.length > 0) {
                    contadores.forEach(contador => {
                        contador.textContent = data.count;
                    });
                    console.log('Contador del carrito actualizado: ' + data.count);
                }
            })
            .catch(error => console.error('Error al actualizar contador:', error));
    }

    // Ejecutar la actualización del contador al cargar la página
    actualizarContadorCarrito();

    // Función para mostrar notificación
    function mostrarNotificacion(mensaje, tipo = 'success') {
        // Eliminar notificaciones existentes
        const notificacionesExistentes = document.querySelectorAll('.alert');
        notificacionesExistentes.forEach(notif => notif.remove());

        const notificacion = document.createElement('div');
        notificacion.className = `alert alert-${tipo} alert-dismissible fade show`;
        notificacion.style.position = 'fixed';
        notificacion.style.top = '20px';
        notificacion.style.right = '20px';
        notificacion.style.zIndex = '1050';
        notificacion.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(notificacion);

        // Eliminar la notificación después de 5 segundos
        setTimeout(() => {
            notificacion.style.opacity = '0';
            setTimeout(() => notificacion.remove(), 500);
        }, 5000);
    }

    // Actualizar el total del carrito
    function actualizarTotal() {
        let total = 0;
        const subtotales = document.querySelectorAll('.item-subtotal');
        subtotales.forEach(subtotal => {
            total += parseFloat(subtotal.textContent);
        });

        const totalElement = document.querySelector('.total');
        if (totalElement) {
            totalElement.textContent = `€${total.toFixed(2)}`;
        }
    }

    // Manejar el formulario de agregar al carrito
    const formAgregarCarrito = document.querySelector('.add-to-cart-form');
    if (formAgregarCarrito) {
        formAgregarCarrito.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data); // Para debugging
                if (data.success) {
                    mostrarNotificacion(data.message);
                    actualizarContadorCarrito();
                } else {
                    mostrarNotificacion(data.message || 'Error al agregar al carrito', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al agregar al carrito', 'danger');
            });
        });
    }

    // Manejar la eliminación de productos del carrito
    const botonesEliminar = document.querySelectorAll('.cart-item .btn-danger');
    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();

            // Obtener referencias 
                const form = this.closest('form');
                const url = form.action;
                const token = document.querySelector('meta[name="csrf-token"]').content;
            const confirmModal = document.getElementById('confirmDeleteModal');
            
            if (confirmModal) {
                // Configurar el modal para eliminar producto
                const modalTitle = confirmModal.querySelector('.modal-title');
                const modalBody = confirmModal.querySelector('.modal-body p');
                const confirmBtn = confirmModal.querySelector('#btnConfirmDelete');
                
                if (modalTitle) modalTitle.textContent = 'Confirmar eliminación';
                if (modalBody) modalBody.textContent = '¿Estás seguro de que deseas eliminar este producto?';
                
                // Mostrar el modal
                const bsModal = new bootstrap.Modal(confirmModal);
                bsModal.show();
                
                // Configurar el botón de confirmación
                if (confirmBtn) {
                    // Eliminar listeners previos
                    const newBtn = confirmBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
                    
                    newBtn.addEventListener('click', () => {
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            bsModal.hide();
                            if (data.success) {
                                mostrarNotificacion(data.message);
                                // Eliminar el elemento del DOM
                                const cartItem = boton.closest('.cart-item');
                                if (cartItem) {
                                    cartItem.remove();
                                }
                                actualizarContadorCarrito();
                                actualizarTotal();

                                // Si no hay más items, mostrar el mensaje de carrito vacío
                                const cartItems = document.querySelectorAll('.cart-item');
                                if (cartItems.length === 0) {
                                    const cartSection = document.querySelector('.cart.section');
                                    if (cartSection) {
                                        cartSection.innerHTML = `
                                        <div class="container">
                                            <div class="section-title text-center">
                                                <h2>Mi Carrito de Compras</h2>
                                            </div>
                                            <div class="empty-cart text-center py-5">
                                                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                                                <h3 class="mb-3">No tienes ningún producto en tu carrito</h3>
                                                <p class="mb-4">Tu carrito está vacío. ¡Añade algunos productos!</p>
                                                <a href="/" class="btn btn-primary btn-lg">
                                                    <i class="bi bi-arrow-left me-2"></i> Volver a la Tienda
                                                </a>
                                            </div>
                                        </div>
                                        `;
                                    }
                                }
                            } else {
                                mostrarNotificacion(data.message || 'Error al eliminar el producto', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            bsModal.hide();
                            mostrarNotificacion('Error al eliminar el producto', 'danger');
                        });
                    });
                }
            } else {
                // Fallback a la implementación anterior si no hay modal
                if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        mostrarNotificacion(data.message);
                        // Eliminar el elemento del DOM
                            const cartItem = boton.closest('.cart-item');
                        if (cartItem) {
                            cartItem.remove();
                        }
                        actualizarContadorCarrito();
                        actualizarTotal();

                        // Si no hay más items, mostrar el mensaje de carrito vacío
                        const cartItems = document.querySelectorAll('.cart-item');
                        if (cartItems.length === 0) {
                            const cartSection = document.querySelector('.cart.section');
                            if (cartSection) {
                                cartSection.innerHTML = `
                                <div class="container">
                                    <div class="section-title text-center">
                                        <h2>Mi Carrito de Compras</h2>
                                    </div>
                                    <div class="empty-cart text-center py-5">
                                        <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                                        <h3 class="mb-3">No tienes ningún producto en tu carrito</h3>
                                        <p class="mb-4">Tu carrito está vacío. ¡Añade algunos productos!</p>
                                        <a href="/" class="btn btn-primary btn-lg">
                                            <i class="bi bi-arrow-left me-2"></i> Volver a la Tienda
                                        </a>
                                    </div>
                                </div>
                                `;
                            }
                        }
                    } else {
                        mostrarNotificacion(data.message || 'Error al eliminar el producto', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarNotificacion('Error al eliminar el producto', 'danger');
                });
                }
            }
        });
    });

    // Manejar botones de incrementar cantidad
    const botonesIncrementar = document.querySelectorAll('.btn-increment');
    botonesIncrementar.forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const token = document.querySelector('meta[name="csrf-token"]').content;

            // Verificar si la cantidad actual ha alcanzado el límite máximo (50)
            const cantidadElement = this.parentElement.querySelector('span');
            const cantidadActual = parseInt(cantidadElement.textContent);

            if (cantidadActual >= 50) {
                mostrarNotificacion('No puedes agregar más de 50 unidades de este producto', 'warning');
                return;
            }

            fetch(`/carrito/incrementar/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Actualizar la cantidad y el subtotal en la interfaz
                    const cartItem = this.closest('.cart-item');
                    const cantidadElement = this.parentElement.querySelector('span');
                    const subtotalElement = cartItem.querySelector('.item-subtotal');

                    cantidadElement.textContent = data.cantidad;
                    subtotalElement.textContent = data.subtotal.toFixed(2);

                    // No mostrar notificación para incrementar
                    // mostrarNotificacion(data.message);
                    actualizarContadorCarrito();
                    actualizarTotal();
                } else {
                    mostrarNotificacion(data.message || 'Error al incrementar la cantidad', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al incrementar la cantidad', 'danger');
            });
        });
    });

    // Manejar botones de decrementar cantidad
    const botonesDecrementar = document.querySelectorAll('.btn-decrement');
    let productoActualId = null;
    let botonActual = null;

    botonesDecrementar.forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const cantidadElement = this.parentElement.querySelector('span');
            const cantidadActual = parseInt(cantidadElement.textContent);

            // Si la cantidad es 1, no hacer nada y mostrar mensaje
            if (cantidadActual === 1) {
                mostrarNotificacion('La cantidad mínima es 1. Use el botón eliminar para quitar el producto', 'warning');
                return;
            }

            // Si la cantidad es mayor a 1, proceder normalmente
            decrementarProducto(id, this);
        });
    });

    // Función para decrementar la cantidad de un producto
    function decrementarProducto(id, boton) {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/carrito/decrementar/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.eliminado) {
                    // Si el producto fue eliminado (cantidad llegó a 0)
                    const cartItem = boton.closest('.cart-item');
                    cartItem.remove();

                    // Si no hay más items, mostrar el mensaje de carrito vacío
                    const cartItems = document.querySelectorAll('.cart-item');
                    if (cartItems.length === 0) {
                        const cartSection = document.querySelector('.cart.section');
                        if (cartSection) {
                            cartSection.innerHTML = `
                            <div class="container">
                                <div class="section-title text-center">
                                    <h2>Mi Carrito de Compras</h2>
                                </div>
                                <div class="empty-cart text-center py-5">
                                    <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                                    <h3 class="mb-3">No tienes ningún producto en tu carrito</h3>
                                    <p class="mb-4">Tu carrito está vacío. ¡Añade algunos productos!</p>
                                    <a href="/" class="btn btn-primary btn-lg">
                                        <i class="bi bi-arrow-left me-2"></i> Volver a la Tienda
                                    </a>
                                </div>
                            </div>
                            `;
                        }
                    }
                } else {
                    // Actualizar la cantidad y el subtotal en la interfaz
                    const cartItem = boton.closest('.cart-item');
                    const cantidadElement = boton.parentElement.querySelector('span');
                    const subtotalElement = cartItem.querySelector('.item-subtotal');

                    cantidadElement.textContent = data.cantidad;
                    subtotalElement.textContent = data.subtotal.toFixed(2);

                    // No mostrar notificación para decrementar
                    // Pero mantener la actualización de contadores
                    actualizarContadorCarrito();
                    actualizarTotal();
                }

                // Solo mostrar notificación si se elimina el producto completamente
                if (data.eliminado) {
                    mostrarNotificacion(data.message);
                }
            } else {
                mostrarNotificacion(data.message || 'Error al decrementar la cantidad', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al decrementar la cantidad', 'danger');
        });
    }

    // Manejar el vaciado del carrito
    const formVaciarCarrito = document.querySelector('.cart.section form[action*="vaciar"]');
    if (formVaciarCarrito) {
        formVaciarCarrito.addEventListener('submit', function(e) {
            e.preventDefault();

            // Usar el modal personalizado en lugar de confirm()
            const confirmModal = document.getElementById('confirmDeleteModal');
            if (confirmModal) {
                // Configurar el modal para vaciar carrito
                const modalTitle = confirmModal.querySelector('.modal-title');
                const modalBody = confirmModal.querySelector('.modal-body p');
                const confirmBtn = confirmModal.querySelector('#btnConfirmDelete');
                
                if (modalTitle) modalTitle.textContent = 'Confirmar vaciado del carrito';
                if (modalBody) modalBody.textContent = '¿Estás seguro de que deseas vaciar el carrito?';
                
                // Mostrar el modal
                const bsModal = new bootstrap.Modal(confirmModal);
                bsModal.show();
                
                // Configurar el botón de confirmación
                if (confirmBtn) {
                    // Eliminar listeners previos
                    const newBtn = confirmBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
                    
                    newBtn.addEventListener('click', () => {
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(this.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                            bsModal.hide();
                    if (data.success) {
                        mostrarNotificacion(data.message);

                        // Vaciar el contenido del carrito
                        const cartSection = document.querySelector('.cart.section');
                        if (cartSection) {
                            cartSection.innerHTML = `
                            <div class="container">
                                <div class="section-title text-center">
                                    <h2>Mi Carrito de Compras</h2>
                                </div>
                                <div class="empty-cart text-center py-5">
                                    <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                                    <h3 class="mb-3">No tienes ningún producto en tu carrito</h3>
                                    <p class="mb-4">Tu carrito está vacío. ¡Añade algunos productos!</p>
                                    <a href="/" class="btn btn-primary btn-lg">
                                        <i class="bi bi-arrow-left me-2"></i> Volver a la Tienda
                                    </a>
                                </div>
                            </div>
                            `;
                        }

                        actualizarContadorCarrito();
                    } else {
                        mostrarNotificacion(data.message || 'Error al vaciar el carrito', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                            bsModal.hide();
                    mostrarNotificacion('Error al vaciar el carrito', 'danger');
                });
                    });
                }
            } else {
                // Fallback si no existe el modal
                this.submit();
            }
        });
    }

    // Crear el modal de confirmación
    const modalHTML = `
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirmar eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este producto del carrito?</p>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Esta acción no se puede deshacer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                        <i class="bi bi-trash me-2"></i>
                        Sí, eliminar producto
                    </button>
                </div>
            </div>
        </div>
    </div>
    `;

    // Agregar el modal al final del documento
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Crear la instancia del modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
});
@extends('layouts.layout')

@section('title', 'Detalle de Pedido #' . ($pedido->codigo ?? $pedido->id))

@section('content')
{{-- Información de depuración --}}
@if(config('app.debug'))
<div class="container my-3">
    <div class="alert alert-info">
        <h5>Información de depuración (solo visible en modo desarrollo)</h5>
        <hr>
        <p><strong>ID del pedido:</strong> {{ $pedido->id }}</p>
        <p><strong>Código:</strong> {{ $pedido->codigo }}</p>
        <p><strong>Estado:</strong> {{ $pedido->estado }}</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
        <p><strong>Productos:</strong> {{ count($pedido->detalles) }}</p>
    </div>
</div>
@endif

<div class="pedido-detalle-page py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h2 mb-0">
                        <i class="bi bi-bag me-2"></i>Detalle de Pedido #{{ $pedido->codigo ?? $pedido->id }}
                    </h1>
                    <a href="{{ route('perfil') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver a mi perfil
                    </a>
                </div>
                <hr>
            </div>
        </div>

        <!-- Barra de estado del pedido -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Estado del Pedido</h5>

                        <!-- Estado actual -->
                        <div class="current-status mb-4">
                            <span class="status-badge status-{{ strtolower($pedido->estado) }} d-inline-block mb-2 px-3 py-1 rounded-pill">
                                @switch($pedido->estado)
                                    @case('confirmado')
                                        <i class="bi bi-check-circle-fill me-1"></i> Aceptado
                                        @break
                                    @case('en_proceso')
                                        <i class="bi bi-gear-fill me-1"></i> Elaborando
                                        @break
                                    @case('terminado')
                                        <i class="bi bi-bag-check-fill me-1"></i> Listo para Recoger
                                        @break
                                    @case('recogido')
                                        <i class="bi bi-check2-all me-1"></i> Recogido
                                        @break
                                    @case('cancelado')
                                        <i class="bi bi-x-circle-fill me-1"></i> Cancelado
                                        @break
                                    @default
                                        <i class="bi bi-hourglass-split me-1"></i> Pagado
                                @endswitch
                            </span>

                            @if($pedido->estado == 'pagado')
                                <div class="mt-2">
                                    <form action="{{ route('perfil.cancelar-pedido', $pedido->id) }}" method="POST" class="cancel-order-form">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.')">
                                            <i class="bi bi-x-circle me-1"></i> Cancelar pedido
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Rastreador de progreso -->
                        <div class="order-progress-tracker" id="orderProgressTracker">
                            <div class="progress-line"></div>
                            <div class="progress-step" data-step="pendiente">
                                <div class="progress-icon pendiente">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div class="progress-label">Pagado</div>
                                <div class="progress-desc">Pedido recibido</div>
                            </div>
                            <div class="progress-step" data-step="confirmado">
                                <div class="progress-icon confirmado">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="progress-label">Aceptado</div>
                                <div class="progress-desc">Pedido aceptado por la tienda</div>
                            </div>
                            <div class="progress-step" data-step="en_proceso">
                                <div class="progress-icon en_proceso">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <div class="progress-label">Elaborando</div>
                                <div class="progress-desc">Tu pedido está siendo preparado</div>
                            </div>
                            <div class="progress-step" data-step="terminado">
                                <div class="progress-icon terminado">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                                <div class="progress-label">Listo</div>
                                <div class="progress-desc">Para recoger</div>
                            </div>
                            <div class="progress-step" data-step="recogido">
                                <div class="progress-icon recogido">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                                <div class="progress-label">Recogido</div>
                                <div class="progress-desc">Entregado al cliente</div>
                            </div>
                            <div class="progress-step" data-step="cancelado">
                                <div class="progress-icon cancelado">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="progress-label">Cancelado</div>
                                <div class="progress-desc">Pedido cancelado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información del pedido -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Información del Pedido</h5>
                        <hr>

                        <div class="info-group mb-3">
                            <p class="mb-1"><strong>Número de pedido:</strong> #{{ $pedido->id }}</p>
                            <p class="mb-1"><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Cliente:</strong> {{ $pedido->nombre }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $pedido->email }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
                        </div>

                        <div class="info-group mb-3">
                            <p class="mb-1"><strong>Método de pago:</strong>
                                @if($pedido->metodo_pago == 'tarjeta')
                                    <i class="bi bi-credit-card me-1"></i> Tarjeta
                                @elseif($pedido->metodo_pago == 'paypal')
                                    <i class="bi bi-paypal me-1"></i> PayPal
                                @elseif($pedido->metodo_pago == 'bizum')
                                    <i class="bi bi-phone me-1"></i> Bizum
                                @elseif($pedido->metodo_pago == 'transferencia')
                                    <i class="bi bi-bank me-1"></i> Transferencia
                                @else
                                    <i class="bi bi-cash me-1"></i> {{ ucfirst($pedido->metodo_pago) }}
                                @endif
                            </p>
                        </div>

                        <div class="info-group">
                            <p class="mb-1"><strong>Total del pedido:</strong> <span class="text-primary fw-bold">€{{ number_format($pedido->total, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos del pedido -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>
                        <hr>

                        <div class="products-list">
                            @foreach($pedido->detalles as $detalle)
                                <div class="product-item p-3 mb-3 border rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1">{{ $detalle->nombre_producto }}</h6>
                                            <div class="text-muted small">
                                                Precio unitario: €{{ number_format($detalle->precio_unitario, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                            <div class="d-flex justify-content-md-end align-items-center">
                                                <span class="badge bg-secondary me-2">{{ $detalle->cantidad }} uds.</span>
                                                <span class="fw-bold">€{{ number_format($detalle->subtotal, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-total mt-4 text-end">
                            <h5>Total: <span class="text-primary">€{{ number_format($pedido->total, 2) }}</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notas adicionales -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Información Adicional</h5>
                        <hr>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Recuerda:</strong> Recibirás notificaciones por correo electrónico cuando el estado de tu pedido cambie.
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('perfil') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-left me-1"></i> Volver a mi perfil
                            </a>
                            @if($pedido->estado == 'terminado')
                                <a href="{{ route('index') }}" class="btn btn-primary">
                                    <i class="bi bi-shop me-1"></i> Visitar la tienda
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para la barra de progreso */
    .order-progress-tracker {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
        position: relative;
    }

    .progress-line {
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #f0f0f0;
        z-index: 1;
    }

    .progress-step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .progress-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        margin-bottom: 10px;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #f0f0f0;
    }

    .progress-label {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .progress-desc {
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        max-width: 100px;
    }

    /* Estilos para los estados de progreso */
    .progress-icon.pendiente {
        background-color: #17a2b8;
        box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.2);
    }

    .progress-icon.confirmado {
        background-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }

    .progress-icon.en_proceso {
        background-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
    }

    .progress-icon.terminado {
        background-color: #fd7e14;
        box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.2);
    }

    .progress-icon.recogido {
        background-color: #6f42c1;
        box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.2);
    }

    .progress-icon.cancelado {
        background-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }

    /* Estilos para el estado activo */
    .progress-step.active .progress-icon {
        transform: scale(1.1);
    }

    .progress-step.current .progress-icon {
        transform: scale(1.2);
        box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.3);
    }

    /* Estilos para las etiquetas de estado */
    .status-badge {
        font-size: 14px;
        font-weight: 600;
    }

    .status-pagado {
        background-color: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }

    .status-confirmado {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-en_proceso {
        background-color: rgba(0, 123, 255, 0.15);
        color: #007bff;
    }

    .status-terminado {
        background-color: rgba(253, 126, 20, 0.15);
        color: #fd7e14;
    }

    .status-recogido {
        background-color: rgba(111, 66, 193, 0.15);
        color: #6f42c1;
    }

    .status-cancelado {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-progress-tracker {
            flex-direction: column;
            align-items: flex-start;
            margin-left: 20px;
        }

        .progress-line {
            top: 0;
            bottom: 0;
            left: 25px;
            right: auto;
            width: 3px;
            height: auto;
        }

        .progress-step {
            flex-direction: row;
            align-items: flex-start;
            margin-bottom: 20px;
            width: 100%;
        }

        .progress-icon {
            margin-right: 15px;
            margin-bottom: 0;
        }

        .progress-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .progress-desc {
            max-width: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el estado del pedido actual
        const estadoActual = '{{ strtolower($pedido->estado) }}';
        const progressTracker = document.getElementById('orderProgressTracker');
        const progressSteps = progressTracker.querySelectorAll('.progress-step');

        // Definir el orden de los pasos
        const pasos = ['pendiente', 'confirmado', 'en_proceso', 'terminado', 'recogido', 'cancelado'];

        // Si el estado es cancelado, solo marcar ese paso
        if (estadoActual === 'cancelado') {
            // Encontrar el paso cancelado
            progressSteps.forEach(step => {
                if (step.dataset.step === 'cancelado') {
                    step.classList.add('active');
                    step.classList.add('current');
                }
            });
        } else {
            // Para otros estados, marcar todos los pasos hasta el actual
            const estadoIndex = pasos.indexOf(estadoActual);

            if (estadoIndex !== -1) {
                progressSteps.forEach((step, index) => {
                    // Solo procesar los pasos normales (no cancelado)
                    if (step.dataset.step !== 'cancelado') {
                        const stepIndex = pasos.indexOf(step.dataset.step);

                        if (stepIndex <= estadoIndex) {
                            step.classList.add('active');
                        }

                        if (stepIndex === estadoIndex) {
                            step.classList.add('current');
                        }
                    }
                });
            }
        }
    });
</script>
@endsection

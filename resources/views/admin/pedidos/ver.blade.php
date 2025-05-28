@extends('admin.layouts.admin')

@section('title', 'Detalles del Pedido #' . ($pedido->codigo ?? $pedido->id))

@section('content')
    <div class="admin-toolbar">
        <h3 class="mb-0">Detalles del Pedido #{{ $pedido->codigo ?? $pedido->id }}</h3>
        <div>
            <a href="{{ route('admin.pedidos') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Pedidos
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-box2"></i> Productos del Pedido
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Tamaño</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->detalles as $detalle)
                                    <tr>
                                        <td>
                                            <div class="product-info">
                                                @if($detalle->producto && $detalle->producto->imagen)
                                                    <img src="{{ asset('img/productos/' . $detalle->producto->imagen) }}" alt="{{ $detalle->nombre_producto }}" class="product-image" style="max-width: 60px; max-height: 60px; border-radius: 5px;">
                                                @else
                                                    <div class="product-image bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 5px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="product-details">
                                                    <div class="product-name">{{ $detalle->nombre_producto }}</div>
                                                    <div class="product-price">ID: {{ $detalle->producto_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                @if($detalle->tamano == 'normal')
                                                    Normal
                                                @elseif($detalle->tamano == 'grande')
                                                    Grande
                                                @elseif($detalle->tamano == 'muygrande')
                                                    Muy Grande
                                                @else
                                                    {{ ucfirst($detalle->tamano ?? 'Normal') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>€{{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>€{{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td class="order-total">€{{ number_format($pedido->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person"></i> Información del Cliente
                </div>
                <div class="card-body">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $pedido->nombre }}</div>

                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $pedido->email }}</div>

                    <div class="info-label">Teléfono:</div>
                    <div class="info-value">{{ $pedido->telefono ?? 'No proporcionado' }}</div>

                    <div class="info-label">Usuario ID:</div>
                    <div class="info-value">{{ $pedido->user_id }}</div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle"></i> Información de la Compra
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">ID del Pedido</div>
                            <div class="info-value">{{ $pedido->id }}</div>

                            <div class="info-label">Código del Pedido</div>
                            <div class="info-value">#{{ $pedido->codigo ?? $pedido->id }}</div>

                            <div class="info-label">Fecha de Compra</div>
                            <div class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>

                            <div class="info-label">Método de Pago</div>
                            <div class="info-value">{{ ucfirst($pedido->metodo_pago) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Estado:</div>
                            <div class="info-value">
                                <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </div>

                            <div class="order-summary mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>€{{ number_format($pedido->total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Total:</strong></span>
                                    <span class="order-total">€{{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>

                            @if ($pedido->notas)
                                <div class="mt-3">
                                    <h6>Notas sobre el pedido:</h6>
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <p class="card-text" style="white-space: pre-line;">{{ $pedido->notas }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="order-status-form mt-4">
                                <form action="{{ route('admin.pedidos.actualizar-estado', $pedido->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Actualizar Estado:</label>
                                        <select class="form-select" id="estado" name="estado">
                                            <option value="confirmado" {{ $pedido->estado == 'confirmado' ? 'selected' : '' }}>Aceptado</option>
                                            <option value="en_proceso" {{ $pedido->estado == 'en_proceso' ? 'selected' : '' }}>Elaborando</option>
                                            <option value="terminado" {{ $pedido->estado == 'terminado' ? 'selected' : '' }}>Terminado - Listo para recoger</option>
                                            <option value="recogido" {{ $pedido->estado == 'recogido' ? 'selected' : '' }}>Recogido</option>
                                            <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            Al cambiar el estado se enviará un correo automáticamente al cliente informándole de la actualización.
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comentario" class="form-label">Nota interna:</label>
                                        <textarea class="form-control" id="comentario" name="comentario" rows="2" placeholder="Añadir nota sobre este pedido (visible sólo para administradores)"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-check2-circle me-1"></i> Actualizar Estado
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .product-info {
        display: flex;
        align-items: center;
    }

    .product-details {
        margin-left: 15px;
    }

    .product-name {
        font-weight: 600;
        color: var(--heading-color);
        margin-bottom: 3px;
    }

    .product-price {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
    }

    .status-confirmado {
        background-color: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }

    .status-en_proceso {
        background-color: rgba(0, 123, 255, 0.15);
        color: #007bff;
    }

    .status-terminado {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .status-recogido {
        background-color: rgba(111, 66, 193, 0.15);
        color: #6f42c1;
    }

    .status-cancelado {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .info-label {
        font-weight: 600;
        color: var(--heading-color);
        margin-top: 1rem;
        margin-bottom: 0.3rem;
    }

    .info-value {
        margin-bottom: 1rem;
    }

    .order-status-form {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px dashed var(--border-color);
    }
</style>
@endpush

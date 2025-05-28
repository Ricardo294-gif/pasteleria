@extends('layouts.layout')

@section('title', 'Carrito de Compras')

@section('content')
    <section class="cart section">
        <div class="container">
            <div class="section-title text-center">
                <h2>Carrito de Compras</h2>
                <p>Revisa tus productos seleccionados</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($items->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-items">
                            @foreach($items as $item)
                                <div class="cart-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <img src="{{ asset('img/productos/' . $item->producto->imagen) }}"
                                                 alt="{{ $item->producto->nombre }}"
                                                 class="img-fluid">
                                        </div>
                                        <div class="col-md-6">
                                            <h4>{{ $item->producto->nombre }}</h4>
                                            <p class="price">€{{ number_format($item->precio_unitario, 2) }}</p>
                                            <div class="quantity">
                                                <span>Cantidad: {{ $item->cantidad }}</span>
                                                <span class="subtotal">Subtotal: €{{ number_format($item->subtotal, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <form action="{{ route('carrito.eliminar', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3>Resumen del Pedido</h3>
                            <div class="summary-item">
                                <span>Total:</span>
                                <span class="total">€{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="cart-actions">
                                <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i> Vaciar Carrito
                                    </button>
                                </form>
                                <a href="{{ route('compra') }}" class="btn" style="background-color: #ff7070; color: white; border-color: #ff7070;">
                                    <i class="bi bi-cart-check"></i> Proceder al Pago
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <p>Tu carrito está vacío</p>
                    <a href="{{ route('index') }}" class="btn" style="background-color: #ff7070; color: white; border-color: #ff7070;">
                        <i class="bi bi-arrow-left"></i> Volver a la Tienda
                    </a>
                </div>
            @endif
        </div>
    </section>

    <style>
        .cart {
            padding: 60px 0;
        }

        .cart-item {
            background: var(--surface-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .cart-item img {
            border-radius: 8px;
        }

        .cart-item h4 {
            margin-bottom: 10px;
            color: var(--heading-color);
        }

        .cart-item .price {
            color: var(--accent-color);
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .cart-item .quantity {
            display: flex;
            justify-content: space-between;
            color: var(--text-color);
        }

        .cart-summary {
            background: var(--surface-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item .total {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .cart-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .cart-actions .btn {
            flex: 1;
        }
    </style>
@endsection

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido Entregado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ff7070;
            margin: 0;
            font-size: 28px;
        }
        .order-info {
            background-color: #f8f1ff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-info h2 {
            margin-top: 0;
            color: #4a4a4a;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .status {
            display: inline-block;
            background-color: rgba(111, 66, 193, 0.15);
            color: #6f42c1;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f9f9f9;
        }
        .feedback-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ff7070;
        }
        .products-list {
            margin-top: 15px;
        }

        .product-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .review-btn {
            display: inline-block;
            background-color: #ff7070;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        .review-btn:hover {
            background-color: #e55c5c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>¡Gracias por tu compra!</p>
    </div>

    <p>Hola {{ $pedido->nombre }},</p>

    <p>Confirmamos que tu pedido ha sido <span class="status">entregado</span> y recogido satisfactoriamente.</p>

    <div class="order-info">
        <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
        <p><strong>Estado:</strong> Entregado</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
    </div>

    <div class="feedback-section">
        <h3>¿Qué te ha parecido?</h3>
        <p>Nos encantaría conocer tu opinión sobre nuestros productos y servicio. Tu feedback nos ayuda a mejorar.</p>
        <p>Haz clic en los productos que compraste para dejar una reseña:</p>

        <div class="products-list">
            @foreach($pedido->detalles as $detalle)
                <div class="product-item">
                    <p><strong>{{ $detalle->producto->nombre }}</strong></p>
                    <a href="{{ route('producto.detalle', $detalle->producto->id) }}#review-section" class="review-btn">Dejar una reseña</a>
                </div>
            @endforeach
        </div>
    </div>

    <p>¡Esperamos que disfrutes de nuestros productos! Gracias por confiar en Mi Sueño Dulce.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

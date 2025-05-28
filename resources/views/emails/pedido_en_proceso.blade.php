<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Pedido en Elaboración</title>
    <style>
        /* Estilos para el email */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px 0;
        }
        h1 {
            color: #ff7070;
            font-size: 24px;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            border-left: 4px solid #ff7070;
        }
        .item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            margin-top: 15px;
            text-align: right;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff7070;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .status {
            font-weight: bold;
            color: #ff7070;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="Mi Sueño Dulce">
        </div>
        <div class="content">
            <h1>¡Hola {{ $pedido->nombre }}!</h1>

            <p>Nos complace informarte que tu pedido está actualmente <span class="status">elaborándose</span> y está siendo preparado.</p>

            <div class="order-info">
                <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
                <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
                <p><strong>Estado:</strong> Elaborando</p>
                <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
            </div>

            <h3>Productos en tu pedido:</h3>
            @foreach($pedido->detalles as $detalle)
            <div class="item">
                <p>
                    <strong>{{ $detalle->producto->nombre }}</strong><br>
                    Cantidad: {{ $detalle->cantidad }} x €{{ number_format($detalle->precio_unitario, 2) }}<br>
                    Subtotal: €{{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                </p>
            </div>
            @endforeach

            <div class="total">
                Total del Pedido: €{{ number_format($pedido->total, 2) }}
            </div>

            <p>En estos momentos estamos preparando tu pedido con mucho cuidado. Recibirás otro correo electrónico cuando esté listo para recoger.</p>

            <p>Si tienes alguna pregunta, no dudes en ponerte en contacto con nosotros.</p>

            <a href="{{ route('index') }}" class="btn">Visitar nuestra tienda</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
            <p>Calle Principal 123, Ciudad, País</p>
        </div>
    </div>
</body>
</html>

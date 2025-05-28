<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido Cancelado</title>
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
            background-color: #fff4e5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
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
        .button {
            display: inline-block;
            background-color: #ff7070;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
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
        .status {
            color: #dc3545;
            font-weight: bold;
        }
        .notice {
            background-color: #fff8f8;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>Información sobre tu pedido</p>
    </div>

    <p>Hola {{ $pedido->nombre }},</p>

    <p>Lamentamos informarte que tu pedido ha sido <span class="status">cancelado</span>.</p>

    <div class="order-info">
        <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
        <p><strong>Estado:</strong> Cancelado</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
    </div>

    <div class="notice">
        <p>Si crees que ha habido un error o necesitas más información sobre la cancelación, por favor contáctanos lo antes posible.</p>
    </div>

    <p>Lamentamos las molestias que esto pueda causarte. Si deseas realizar un nuevo pedido, puedes visitar nuestra tienda en cualquier momento.</p>

    <p>Agradecemos tu comprensión.</p>

    <p>¡Gracias por elegir Mi Sueño Dulce!</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

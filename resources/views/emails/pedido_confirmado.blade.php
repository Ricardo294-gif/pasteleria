<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido Aceptado</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>¡Gracias por tu pedido!</p>
    </div>

    <p>Hola {{ $pedido->nombre }},</p>

    <p>¡Nos complace informarte que tu pedido ha sido <strong>aceptado</strong>!</p>

    <div class="order-info">
        <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
        <p><strong>Estado:</strong> Aceptado</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
    </div>

    <p>Tu pedido ha sido recibido y está siendo procesado. Te mantendremos informado sobre su progreso.</p>

    <p>Recuerda que podrás recoger tu pedido en nuestra tienda cuando esté listo. Te enviaremos un correo electrónico para avisarte.</p>

    <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>

    <p>¡Gracias por elegir Mi Sueño Dulce!</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

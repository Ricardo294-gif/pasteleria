<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido Completado</title>
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
            background-color: #f0fff0;
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
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
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
        .pickup-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ff7070;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>¡Tu pedido está listo!</p>
    </div>

    <p>Hola {{ $pedido->nombre }},</p>

    <p>¡Buenas noticias! Tu pedido está <span class="status">completado</span> y listo para ser recogido.</p>

    <div class="order-info">
        <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
        <p><strong>Estado:</strong> Completado - Listo para recoger</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
    </div>

    <div class="pickup-info">
        <h3>Información de Recogida</h3>
        <p>Tu pedido te está esperando en nuestra tienda. Puedes recogerlo en cualquier momento durante nuestro horario comercial:</p>
        <p><strong>Dirección:</strong> Calle Principal 123, Ciudad</p>
        <p><strong>Horario:</strong> Lunes a Viernes de 9:00 a 20:00, Sábados de 10:00 a 18:00</p>
        <p>Por favor, trae contigo tu identificación y el número de pedido para una recogida rápida.</p>
    </div>

    <p>Si tienes alguna pregunta o necesitas ajustar el horario de recogida, no dudes en contactarnos.</p>

    <p>¡Gracias por elegir Mi Sueño Dulce! Esperamos que disfrutes tu pedido.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

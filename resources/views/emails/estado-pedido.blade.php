<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de tu pedido - Mi Sueño Dulce</title>
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
            padding: 0;
        }
        .content {
            background-color: #fff4e5;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #666;
            margin-top: 30px;
        }
        .status {
            background-color: #ff7070;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px 0;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>Actualización de tu pedido</p>
    </div>

    <div class="content">
        <p>Hola {{ $pedido->nombre }},</p>

        <p>Te escribimos para informarte que tu pedido #{{ $pedido->codigo }} ha sido <strong>{{ $estadoActual }}</strong>.</p>

        <div class="status">
            Estado actual: {{ ucfirst($estadoActual) }}
        </div>

        <div class="details">
            <h3>Detalles del pedido:</h3>
            <p><strong>Número de pedido:</strong> #{{ $pedido->codigo }}</p>
            <p><strong>Fecha del pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
        </div>

        @if($pedido->estado == 'terminado')
            <p><strong>¡Tu pedido está listo para recoger!</strong></p>
            <p>Puedes pasar a recogerlo en nuestro local durante nuestro horario de atención.</p>
        @elseif($pedido->estado == 'en_proceso')
            <p>Estamos trabajando en tu pedido con mucho cariño. Te notificaremos cuando esté listo para recoger.</p>
        @elseif($pedido->estado == 'confirmado')
            <p>Hemos recibido tu pedido y pronto comenzaremos a prepararlo.</p>
        @elseif($pedido->estado == 'cancelado')
            <p>Tu pedido ha sido cancelado. Si tienes alguna pregunta, no dudes en contactarnos.</p>
        @endif

        <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>Mi Sueño Dulce &copy; {{ date('Y') }}</p>
    </div>
</body>
</html> 
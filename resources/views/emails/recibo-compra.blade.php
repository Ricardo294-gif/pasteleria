<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Compra - Mi Sueño Dulce</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Amatic+SC:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff4e5;
            color: #4a4a4a;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #ff7070;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            font-family: 'Dancing Script', cursive, Arial, sans-serif;
            margin: 0;
            font-size: 32px;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            font-family: 'Amatic SC', cursive, Arial, sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #37373f;
        }
        .content h3 {
            font-family: 'Amatic SC', cursive, Arial, sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #37373f;
        }
        .footer {
            background-color: #1a1814;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }
        .order-info {
            border: 1px solid #ebebeb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ebebeb;
        }
        th {
            background-color: #f8f8f8;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
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
        .address {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mi Sueño Dulce</h1>
            <p>Recibo de Compra</p>
        </div>

        <div class="content">
            <h2>¡Gracias por tu compra, {{ $compra->nombre }}!</h2>

            <p>Hemos procesado tu pedido correctamente. A continuación encontrarás los detalles de tu compra:</p>

            <div class="order-info">
                <h3>Información del Pedido</h3>
                <p><strong>Número de Pedido:</strong> #{{ $compra->codigo ?? $compra->id }}</p>
                <p><strong>Fecha:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Método de Pago:</strong>
                    @if($compra->metodo_pago == 'tarjeta')
                        Tarjeta de Crédito/Débito
                    @elseif($compra->metodo_pago == 'paypal')
                        PayPal
                    @elseif($compra->metodo_pago == 'bizum')
                        Bizum
                    @elseif($compra->metodo_pago == 'transferencia')
                        Transferencia Bancaria
                    @else
                        {{ ucfirst($compra->metodo_pago) }}
                    @endif
                </p>
            </div>

            <div class="order-details">
                <h3>Productos Comprados</h3>
                <table>
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
                        @foreach($detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>
                                @if($detalle->tamano == 'normal')
                                    Normal
                                @elseif($detalle->tamano == 'grande')
                                    Grande
                                @elseif($detalle->tamano == 'muygrande')
                                    Muy Grande
                                @else
                                    {{ ucfirst($detalle->tamano) }}
                                @endif
                            </td>
                            <td>€{{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>€{{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4">Total</td>
                            <td>€{{ number_format($compra->total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos respondiendo a este correo o llamando a nuestro servicio de atención al cliente.</p>

            <p>Puedes ver el estado de tu pedido en cualquier momento visitando tu perfil en nuestra web:</p>

            <a href="{{ route('perfil') }}" class="button">Ver mis Pedidos</a>
        </div>

        <div class="footer">
            <p>© Copyright <strong>Mi Sueño Dulce</strong> | Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido Cancelado por Cliente</title>
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
            color: #dc3545;
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
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce - Administración</h1>
        <p>Notificación de Cancelación de Pedido</p>
    </div>

    <div class="alert">
        <strong>Alerta:</strong> Un cliente ha cancelado su pedido.
    </div>

    <p>Hola Administrador,</p>

    <p>Te informamos que el cliente <strong>{{ $usuario->name }}</strong> ha cancelado el pedido #{{ $pedido->id }}.</p>

    <div class="order-info">
        <h2>Detalles del Pedido #{{ $pedido->id }}</h2>
        <p><strong>Cliente:</strong> {{ $pedido->nombre }}</p>
        <p><strong>Email:</strong> {{ $pedido->email }}</p>
        <p><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
        <p><strong>Fecha del pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Fecha de cancelación:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Estado:</strong> Cancelado</p>
        <p><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</p>
    </div>

    <h3>Productos en el pedido:</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->detalles as $detalle)
            <tr>
                <td>{{ $detalle->nombre_producto }}</td>
                <td>€{{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>€{{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>€{{ number_format($pedido->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <p>Este pedido ha sido cancelado por el cliente antes de ser procesado.</p>

    <p>Puedes ver más detalles del pedido en el panel de administración.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

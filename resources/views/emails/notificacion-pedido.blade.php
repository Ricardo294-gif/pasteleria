<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Pedido - Mi Sueño Dulce</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #ff7070;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-info {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Pedido Recibido</h1>
        </div>

        <div class="content">
            <div class="order-info">
                <h2>Información del Cliente</h2>
                <p><strong>Número de Pedido:</strong> #{{ $compra->codigo ?? $compra->id }}</p>
                <p><strong>Nombre:</strong> {{ $compra->nombre }}</p>
                <p><strong>Email:</strong> {{ $compra->email }}</p>
                <p><strong>Teléfono:</strong> {{ $compra->telefono }}</p>
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
                <p><strong>Fecha del Pedido:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <h2>Detalles del Pedido</h2>
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
                        <td>{{ $detalle->nombre_producto }}</td>
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
                        <td>€{{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4"><strong>Total</strong></td>
                        <td><strong>€{{ number_format($compra->total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <p>Puedes gestionar este pedido desde el panel de administración:</p>
            <a href="{{ route('admin.pedidos.ver', $compra->id) }}" class="button">Ver Pedido #{{ $compra->codigo ?? $compra->id }} en Panel</a>
        </div>
    </div>
</body>
</html>

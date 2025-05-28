<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Pedido #{{ $pedido->id ?? 'No ID' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <h1>Prueba de visualización de pedido</h1>
            </div>
            <div class="card-body">
                @if(isset($pedido))
                    <h3>Datos básicos del pedido</h3>
                    <ul>
                        <li><strong>ID:</strong> {{ $pedido->id }}</li>
                        <li><strong>Estado:</strong> {{ $pedido->estado }}</li>
                        <li><strong>Total:</strong> €{{ number_format($pedido->total, 2) }}</li>
                        <li><strong>Fecha:</strong> {{ $pedido->created_at }}</li>
                    </ul>

                    <h3>Detalles del pedido</h3>
                    @if(count($pedido->detalles) > 0)
                        <table class="table table-bordered">
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
                        </table>
                    @else
                        <div class="alert alert-warning">No hay detalles para este pedido</div>
                    @endif
                @else
                    <div class="alert alert-danger">No se ha recibido ningún pedido</div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('perfil') }}" class="btn btn-primary">Volver al perfil</a>
            </div>
        </div>
    </div>
</body>
</html>

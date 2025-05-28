<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto en Mi Sueño Dulce</title>
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
            text-align: center;
        }
        .content h2 {
            font-family: 'Amatic SC', cursive, Arial, sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #37373f;
        }
        .product-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            text-align: left;
        }
        .product-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .product-name {
            font-weight: 700;
            font-size: 20px;
            color: #ff7070;
            margin-bottom: 10px;
        }
        .product-description {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .product-price {
            font-weight: 700;
            font-size: 18px;
            color: #37373f;
            margin-bottom: 20px;
        }
        .cta-button {
            display: inline-block;
            background-color: #ff7070;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            margin-top: 15px;
        }
        .footer {
            background-color: #1a1814;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }
        .unsubscribe {
            margin-top: 10px;
            font-size: 11px;
            color: #999;
        }
        .unsubscribe a {
            color: #999;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mi Sueño Dulce</h1>
            <p>Nuevos productos para ti</p>
        </div>

        <div class="content">
            <h2>¡Nuevo producto disponible!</h2>
            <p>Hemos añadido un nuevo producto a nuestra tienda que creemos que te encantará.</p>

            <div class="product-info">
                <img src="{{ url('img/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-image">
                <div class="product-name">{{ $producto->nombre }}</div>
                <div class="product-description">{{ $producto->descripcion }}</div>
                <div class="product-price">€{{ number_format($producto->precio, 2) }}</div>
                <div><strong>Ingredientes:</strong> {{ $producto->ingredientes }}</div>
            </div>

            <p>No esperes más para probarlo. ¡Te va a encantar!</p>

            <a href="{{ url('producto/' . $producto->id) }}" class="cta-button">Ver producto</a>
        </div>

        <div class="footer">
            <p>© Copyright <strong>Mi Sueño Dulce</strong> | Todos los derechos reservados</p>
            <div class="unsubscribe">
                <p>Si no deseas recibir más notificaciones, <a href="{{ route('perfil') }}">actualiza tus preferencias</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>

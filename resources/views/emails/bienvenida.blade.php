<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido/a a Mi Sueño Dulce!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .logo {
            font-family: 'Arial', cursive;
            font-size: 28px;
            color: #ce1212;
            margin-bottom: 10px;
        }
        h1 {
            color: #ce1212;
            margin-bottom: 20px;
        }
        .content {
            background-color: #fdf9f2;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #ce1212;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: 600;
        }
        .section {
            margin: 25px 0;
        }
        .highlight {
            font-weight: bold;
            color: #ce1212;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Mi Sueño Dulce</div>
    </div>

    <div class="content">
        <h1>¡Bienvenido/a {{ $nombre }} {{ $apellido }}!</h1>

        <p>Nos complace darte la bienvenida a <span class="highlight">Mi Sueño Dulce</span>, tu nueva tienda de dulces y postres artesanales. Tu cuenta ha sido creada exitosamente y ahora eres parte de nuestra dulce comunidad.</p>

        <div class="section">
            <h3>¿Qué puedes hacer ahora?</h3>
            <ul>
                <li>Explorar nuestro catálogo de deliciosos productos</li>
                <li>Añadir tus favoritos al carrito</li>
                <li>Realizar pedidos de forma rápida y segura</li>
                <li>Recibir notificaciones sobre nuevos productos y ofertas especiales</li>
            </ul>
        </div>

        <div class="section">
            <h3>Resumen de tu cuenta:</h3>
            <p><strong>Nombre:</strong> {{ $nombre }} {{ $apellido }}</p>
            <p><strong>Correo electrónico:</strong> {{ $email }}</p>
            @if(isset($telefono) && !empty($telefono))
            <p><strong>Teléfono:</strong> {{ $telefono }}</p>
            @endif
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('index') }}" class="button">Visitar la tienda</a>
        </div>
    </div>

    <div class="footer">
        <p>Si tienes alguna pregunta, no dudes en contactarnos respondiendo a este correo o a través de nuestra sección de <a href="{{ route('index') }}#contact">contacto</a>.</p>
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

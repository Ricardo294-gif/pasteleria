<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperación de Contraseña - Mi Sueño Dulce</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fdf9f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #ce1212;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ce1212;">Mi Sueño Dulce</h1>
        </div>

        <div class="content">
            <h2>Recuperación de Contraseña</h2>

            <p>Hola {{ $user->name }},</p>

            <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva contraseña:</p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="button" style="color: #fff;">Restablecer Contraseña</a>
            </p>

            <p>Si no has solicitado este cambio, puedes ignorar este correo y tu contraseña no será modificada.</p>

            <p>Este enlace expirará en 60 minutos.</p>

            <p>Saludos,<br>El equipo de Mi Sueño Dulce</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
            <p>C. Cantalojas, 1, Ibaiondo, 48003 Bilbao, Vizcaya</p>
        </div>
    </div>
</body>
</html>

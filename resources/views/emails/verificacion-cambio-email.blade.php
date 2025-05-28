<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cambio de Correo Electrónico - Mi Sueño Dulce</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #4a4a4a;
            margin: 0;
            padding: 0;
            background-color: #fff4e5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #ff7070;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .code-container {
            background-color: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .note {
            font-size: 13px;
            color: #888;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mi Sueño Dulce</h1>
        </div>
        <div class="content">
            <h2>Verificación de Cambio de Correo</h2>
            <p>Hola,</p>
            <p>Hemos recibido una solicitud para cambiar la dirección de correo electrónico asociada a tu cuenta de Mi Sueño Dulce. Para confirmar este cambio, por favor utiliza el siguiente código de verificación:</p>

            <div class="code-container">
                <div class="verification-code">{{ $code }}</div>
            </div>

            <p>Introduce este código en la página de verificación para completar el proceso.</p>

            <p><strong>¿No has solicitado este cambio?</strong> Si no has solicitado cambiar tu correo electrónico, por favor ignora este mensaje o contacta con nuestro equipo de soporte.</p>

            <div class="note">
                <p>Este código expirará en 10 minutos por razones de seguridad.</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>

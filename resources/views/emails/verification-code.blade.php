<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de Verificación - Mi Sueño Dulce</title>
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
        .code-container {
            background-color: #f8f1ff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .verification-code {
            font-size: 32px;
            letter-spacing: 5px;
            font-weight: bold;
            color: #6f42c1;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            display: inline-block;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .note {
            background-color: #fff4e5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mi Sueño Dulce</h1>
        <p>Código de Verificación para Registro</p>
    </div>

    <p>Hola {{ $nombre }},</p>

    <p>Gracias por registrarte en Mi Sueño Dulce. Para completar tu registro, necesitamos verificar tu dirección de correo electrónico.</p>

    <div class="code-container">
        <p>Tu código de verificación es:</p>
        <div class="verification-code">{{ $code }}</div>
    </div>

    <p>Introduce este código en la página de verificación para finalizar tu registro.</p>

    <div class="note">
        <p><strong>Nota:</strong> Este código expirará en 10 minutos. Si no solicitaste este registro, puedes ignorar este correo.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Mi Sueño Dulce. Todos los derechos reservados.</p>
    </div>
</body>
</html>

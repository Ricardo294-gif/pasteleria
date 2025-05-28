<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Recepción - Mis Dulces Pastelitos</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap');
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ff7070;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .thank-you {
            font-size: 18px;
            margin-bottom: 20px;
            color: #ff7070;
            font-weight: bold;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .cta-button {
            display: inline-block;
            background-color: #ff7070;
            color: white;
            text-decoration: none;
            padding: 10px 30px;
            border-radius: 50px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f1f1f1;
            font-size: 12px;
            color: #777;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 5px;
        }
        .logo {
            margin-bottom: 10px;
        }
        .logo-text {
            font-family: 'Dancing Script', cursive;
            font-size: 32px;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="logo-text">Mi sueño dulce</div>
            </div>
            <h1>¡Gracias por contactarnos!</h1>
        </div>

        <div class="content">
            <p class="thank-you">Hola {{ $name }},</p>

            <p class="message">
                Hemos recibido tu mensaje y te agradecemos por ponerte en contacto con nosotros.
                Nuestro equipo revisará tu solicitud y te responderemos en el menor tiempo posible,
                normalmente dentro de las próximas 24-48 horas.
            </p>

            <p class="message">
                Si tienes alguna consulta urgente, no dudes en llamarnos directamente al:
                <strong>+34 657 488 054</strong>
            </p>

            <a href="{{ route('index') }}" class="cta-button">Visitar nuestra web</a>

        </div>

        <div class="footer">
            <p>© Mis Dulces Pastelitos - Todos los derechos reservados</p>
            <p>C. Cantalojas, 1, Ibaiondo, 48003 Bilbao, Vizcaya</p>
        </div>
    </div>
</body>
</html>

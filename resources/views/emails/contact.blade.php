<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto - Mis Dulces Pastelitos</title>
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
            padding: 20px;
        }
        .message-info {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .message-content {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ff7070;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f1f1f1;
            font-size: 12px;
            color: #777;
        }
        p {
            margin: 10px 0;
        }
        strong {
            color: #ff7070;
        }
        .logo {
            margin-bottom: 10px;
        }
        .logo-text {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
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
            <h1>Nuevo Mensaje de Contacto</h1>
        </div>

        <div class="content">
            <div class="message-info">
                <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
                <p><strong>Email:</strong> {{ $data['email'] }}</p>
                <p><strong>Asunto:</strong> {{ $data['subject'] }}</p>
            </div>

            <div class="message-content">
                <p><strong>Mensaje:</strong></p>
                <p>{{ $data['message'] }}</p>
            </div>
        </div>

        <div class="footer">
            <p>© Mis Dulces Pastelitos - Todos los derechos reservados</p>
            <p>C. Cantalojas, 1, Ibaiondo, 48003 Bilbao, Vizcaya</p>
        </div>
    </div>
</body>
</html>

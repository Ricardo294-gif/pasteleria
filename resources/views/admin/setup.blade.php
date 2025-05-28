<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Administrador - Mi Sueño Dulce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff7070;
            --bg-color: #fff4e5;
            --text-color: #4a4a4a;
            --heading-color: #37373f;
            --surface-color: #ffffff;
            --border-color: #ebebeb;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 500px;
            padding: 30px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .sitename {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin: 0;
        }

        .card {
            background-color: var(--surface-color);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 20px;
            border-bottom: none;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            color: var(--heading-color);
        }

        .form-control {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 112, 112, 0.25);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px;
            font-weight: 500;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #e56464;
            border-color: #e56464;
        }

        .alert {
            border-radius: 10px;
        }

        .setup-info {
            background-color: rgba(255, 112, 112, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .setup-info i {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <h1 class="sitename">Mi sueño dulce</h1>
            <p>Configuración inicial del administrador</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person-gear me-2"></i>Crear Usuario Administrador</h4>
            </div>
            <div class="card-body">
                <div class="setup-info d-flex align-items-start">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        <p class="mb-0">Este formulario te permitirá crear un usuario administrador para gestionar tu sitio web. Este usuario tendrá acceso completo al panel de administración.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.setup.create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de Administrador</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted">La contraseña debe tener al menos 8 caracteres.</small>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Crear Usuario Administrador
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

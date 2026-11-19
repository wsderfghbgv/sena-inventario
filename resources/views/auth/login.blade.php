<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | SENNOVA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f8e7, #f0fbfc);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .logo {
            color: #39A900;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-sena {
            background: #39A900;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-sena:hover {
            background: #318f00;
            color: white;
        }

        .form-control {
            padding: 12px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="logo">SENNOVA</div>

        <h4 class="text-center fw-bold mb-2">
            Bienvenido
        </h4>

        <p class="text-muted text-center mb-4">
            Ingresa tus datos para acceder al inventario.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="correo@ejemplo.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Ingresa tu contraseña"
                    required
                >
            </div>

            <button type="submit" class="btn btn-sena w-100">
                Iniciar sesión
            </button>

        </form>

        <p class="text-center text-muted mt-4 mb-0 small">
            Sistema de gestión de inventario
            <br>
            Proyecto SENNOVA
        </p>

    </div>

</body>
</html>

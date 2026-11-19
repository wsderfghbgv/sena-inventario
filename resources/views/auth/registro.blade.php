<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | SENNOVA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f8e7, #f0fbfc);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            padding: 25px;
        }

        .register-card {
            width: 100%;
            max-width: 450px;
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

    <div class="register-card">

        <div class="logo mb-2">SENNOVA</div>

        <h4 class="text-center fw-bold mb-2">Crear cuenta</h4>

        <p class="text-muted text-center mb-4">
            Regístrate para acceder al sistema de inventario.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre completo</label>
                <input type="text" name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Ingresa tu nombre"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input type="email" name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contraseña</label>
                <input type="password" name="password"
                    class="form-control"
                    placeholder="Mínimo 8 caracteres"
                    required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                    class="form-control"
                    placeholder="Repite tu contraseña"
                    required>
            </div>

            <button type="submit" class="btn btn-sena w-100">
                Crear cuenta
            </button>
        </form>

        <p class="text-center mt-4 mb-0">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}" class="text-success fw-bold">
                Inicia sesión
            </a>
        </p>

    </div>

</body>
</html>

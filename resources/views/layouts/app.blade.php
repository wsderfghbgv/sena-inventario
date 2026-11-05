<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA - Sistema de Inventario</title>
    
    <!-- Enlace CDN de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados para aplicar la identidad visual del SENA / SENNOVA -->
    <style>
        :root {
            --sena-verde: #39A900;
            --sena-verde-oscuro: #2e8600;
        }
        /* Navbar con el verde institucional del SENA */
        .navbar-sena {
            background-color: var(--sena-verde) !important;
        }
        /* Botones principales con el color corporativo */
        .btn-sena-primary {
            background-color: var(--sena-verde);
            border-color: var(--sena-verde);
            color: #fff;
        }
        .btn-sena-primary:hover {
            background-color: var(--sena-verde-oscuro);
            border-color: var(--sena-verde-oscuro);
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Menú de navegación con estilo SENA -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sena mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('productos.index') }}">SENA / SENNOVA - Inventario</a>
            <div class="navbar-nav">
                <a class="nav-link text-white" href="{{ route('productos.index') }}">Productos</a>
                <a class="nav-link text-white" href="{{ route('productos.create') }}">Registrar Nuevo</a>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">SENA / SENNOVA - Inventario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('productos.index') }}">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('locations.index') }}">Ubicaciones</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <a class="nav-link text-white" href="{{ route('productos.create') }}">+ Nuevo Producto</a>
                    <a class="nav-link text-white" href="{{ route('locations.create') }}">+ Nueva Ubicación</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

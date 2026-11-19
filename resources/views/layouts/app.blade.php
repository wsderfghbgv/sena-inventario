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
            --sena-verde: #4DB848;
            --sena-verde-oscuro: #39A900;
            --sena-teal: #2BBFCC;
            --sena-teal-oscuro: #1EA8B5;
        }

        /* Fondo general con tono teal muy suave */
        body.bg-light {
            background-color: #f0fbfc !important;
        }

        /* Navbar con el verde institucional del SENA */
        .navbar-sena {
            background-color: var(--sena-verde) !important;
        }

        /* ===== ESTILO PILL PARA TODOS LOS BOTONES ===== */
        .btn {
            border-radius: 50px !important;
            font-weight: 600 !important;
            padding: 8px 22px !important;
            transition: all 0.2s ease !important;
        }
        .btn-sm {
            padding: 4px 14px !important;
        }

        /* Botón primario — verde SENA */
        .btn-primary {
            background-color: var(--sena-verde) !important;
            border-color: var(--sena-verde) !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--sena-verde-oscuro) !important;
            border-color: var(--sena-verde-oscuro) !important;
            color: #fff !important;
        }

        /* Botón secundario — teal SENA */
        .btn-secondary {
            background-color: var(--sena-teal) !important;
            border-color: var(--sena-teal) !important;
            color: #fff !important;
        }
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: var(--sena-teal-oscuro) !important;
            border-color: var(--sena-teal-oscuro) !important;
            color: #fff !important;
        }

        /* Outline secondary — teal con borde */
        .btn-outline-secondary {
            border-color: var(--sena-teal) !important;
            color: var(--sena-teal) !important;
        }
        .btn-outline-secondary:hover {
            background-color: var(--sena-teal) !important;
            color: #fff !important;
        }

        /* Botón warning — Editar */
        .btn-warning {
            background-color: #f0a500 !important;
            border-color: #f0a500 !important;
            color: #fff !important;
        }
        .btn-warning:hover {
            background-color: #cc8d00 !important;
            border-color: #cc8d00 !important;
            color: #fff !important;
        }

        /* Botón info — Ver */
        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: #fff !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
            color: #fff !important;
        }

        /* Botón danger — Eliminar */
        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }
        .btn-danger:hover {
            background-color: #bd2130 !important;
            border-color: #bd2130 !important;
            color: #fff !important;
        }

        /* Encabezado de tabla con verde oscuro */
        .table-dark {
            background-color: var(--sena-verde-oscuro) !important;
        }

        /* Cards con borde sutil */
        .card {
            border: none;
            border-radius: 12px;
        }

        /* ===== BOTONES DE TABLA (Ver / Editar / Eliminar) ===== */
        /* Ver — verde claro */
        .btn-accion-ver {
            background-color: #4DB848 !important;
            border-color: #4DB848 !important;
            color: #fff !important;
        }
        .btn-accion-ver:hover {
            background-color: #39A900 !important;
            border-color: #39A900 !important;
            color: #fff !important;
        }
        /* Editar — verde oscuro */
        .btn-accion-editar {
            background-color: #39A900 !important;
            border-color: #39A900 !important;
            color: #fff !important;
        }
        .btn-accion-editar:hover {
            background-color: #2e8600 !important;
            border-color: #2e8600 !important;
            color: #fff !important;
        }
        /* Eliminar — rojo (se mantiene para indicar peligro, pero en pill) */
        .btn-accion-eliminar {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }
        .btn-accion-eliminar:hover {
            background-color: #bd2130 !important;
            border-color: #bd2130 !important;
            color: #fff !important;
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
                @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('productos.index') }}">Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('locations.index') }}">Ubicaciones</a>
                        </li>
                        @if(Auth::user()->esAdmin())
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('usuarios.index') }}">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('permisos.index') }}">Permisos</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('entradas.create') }}">Registrar entrada</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('auditoria.index') }}">Auditoría</a>
                            </li>
                        @endif
                    </ul>
                    <div class="navbar-nav align-items-center gap-2">
                        <span class="text-white small">
                            <strong>{{ Auth::user()->name }}</strong>
                            @if(Auth::user()->esAdmin())
                                <span class="badge ms-1" style="background-color: #1565C0;">Admin</span>
                            @elseif(Auth::user()->esInstructor())
                                <span class="badge ms-1" style="background-color: #2BBFCC; color: #fff;">Instructor</span>
                            @else
                                <span class="badge ms-1" style="background-color: #4DB848;">Aprendiz</span>
                            @endif
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light text-white" style="border-radius: 50px;">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-sm btn-light fw-bold" href="{{ route('login') }}" style="color: #39A900; border-radius: 50px;">
                                Iniciar Sesión
                            </a>
                        </li>
                    </ul>
                @endauth
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

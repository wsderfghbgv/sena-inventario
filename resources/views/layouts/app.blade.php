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

        /* ===== ESTILOS PARA MENÚS DESPLEGABLES (DROPDOWNS) ===== */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 8px;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 0.92rem;
            padding: 8px 14px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: #f0fbfc;
            color: #39A900;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: var(--sena-verde);
            color: #fff;
        }
        .nav-link.dropdown-toggle:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Menú de navegación con estilo SENA -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sena mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                <span>SENA / SENNOVA</span>
                <span class="badge bg-light text-dark fw-normal small px-2 py-1" style="font-size: 0.75rem;">Inventario</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto align-items-lg-center">
                        <!-- Menú Desplegable: Inventario -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white fw-semibold px-3" href="#" id="inventarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Inventario
                            </a>
                            <ul class="dropdown-menu shadow-sm" aria-labelledby="inventarioDropdown">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('productos.index') }}">
                                        <span>Productos</span>
                                        @if(Auth::user()->tienePermiso('productos.crear'))
                                            <span class="badge bg-light text-success border ms-2 small">+ Nuevo</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('locations.index') }}">
                                        <span>Ubicaciones</span>
                                        @if(Auth::user()->tienePermiso('locations.crear'))
                                            <span class="badge bg-light text-success border ms-2 small">+ Nueva</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <h6 class="dropdown-header text-uppercase small text-muted">Movimientos</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('entradas.create') }}">
                                        Registrar Entrada
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Menú Desplegable: Administración y Seguridad (Solo Admin) -->
                        @if(Auth::user()->esAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white fw-semibold px-3" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Administración
                                </a>
                                <ul class="dropdown-menu shadow-sm" aria-labelledby="adminDropdown">
                                    <li>
                                        <h6 class="dropdown-header text-uppercase small text-muted">Gestión y Control</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('usuarios.index') }}">
                                            Usuarios y Cuentas
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('permisos.index') }}">
                                            Matriz de Permisos
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <h6 class="dropdown-header text-uppercase small text-muted">Seguridad</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('auditoria.index') }}">
                                            Historial de Auditoría
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>

                    <!-- Menú Desplegable de Usuario y Sesión (A la derecha) -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2 px-3 shadow-sm" 
                                    type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false" 
                                    style="border-radius: 50px; background-color: #ffffff; color: #2e8600; font-weight: 600;">
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->esAdmin())
                                    <span class="badge" style="background-color: #1565C0; color: #fff;">Admin</span>
                                @elseif(Auth::user()->esInstructor())
                                    <span class="badge" style="background-color: #2BBFCC; color: #fff;">Instructor</span>
                                @else
                                    <span class="badge" style="background-color: #4DB848; color: #fff;">Aprendiz</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenuDropdown" style="min-width: 230px;">
                                <li class="px-3 py-2 bg-light rounded-top">
                                    <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-muted small text-truncate">{{ Auth::user()->email }}</div>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary small text-capitalize">{{ Auth::user()->rol_nombre }}</span>
                                        <span class="badge bg-success small text-capitalize ms-1">{{ Auth::user()->estado }}</span>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                            </svg>
                                            <span>Cerrar sesión</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
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

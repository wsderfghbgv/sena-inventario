@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Gestión de Usuarios</h2>
        <p class="text-muted small mb-0">Administración de credenciales, roles normalizados y accesos al sistema (RF-021)</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<!-- Filtros de búsqueda (RF-021) -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('usuarios.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold">Buscar por Nombre o Correo:</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Ej: Carlos o usuario@sena.edu.co" value="{{ request('buscar') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Rol:</label>
                    <select name="rol_id" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ request('rol_id') == $r->id ? 'selected' : '' }}>
                                {{ ucfirst($r->nombre) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Estado:</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    @if(request()->hasAny(['buscar', 'rol_id', 'estado']))
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">Limpiar</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: var(--sena-verde-oscuro); color: #fff;">
                    <tr>
                        <th class="ps-3">Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol (Tabla Roles)</th>
                        <th>Estado</th>
                        <th>Registrado</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        @php
                            $nombreRol = $u->role?->nombre ?? 'Sin Rol';
                        @endphp
                        <tr>
                            <td class="ps-3 fw-semibold">
                                {{ $u->name }}
                                @if(Auth::id() === $u->id)
                                    <span class="badge bg-light text-dark border ms-1 small">Tú</span>
                                @endif
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($nombreRol === 'admin')
                                    <span class="badge" style="background-color: #1565C0;">Administrador</span>
                                @elseif($nombreRol === 'instructor')
                                    <span class="badge" style="background-color: #2BBFCC; color: #fff;">Instructor</span>
                                @elseif($nombreRol === 'aprendiz')
                                    <span class="badge" style="background-color: #4DB848;">Aprendiz</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($nombreRol) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($u->estado === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('usuarios.edit', $u->id) }}" class="btn btn-sm btn-accion-editar">Editar</a>

                                @if(Auth::id() !== $u->id)
                                    <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-accion-eliminar">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No se encontraron usuarios registrados con los criterios seleccionados.
                                @if(request()->hasAny(['buscar', 'rol_id', 'estado']))
                                    <br><a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-link mt-1">Limpiar filtros</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection

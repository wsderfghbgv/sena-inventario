@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Historial de Auditoría</h2>
        <p class="text-muted small mb-0">Registro histórico e inmutable de acciones críticas (RF-023)</p>
    </div>
</div>

<!-- Filtros de búsqueda para auditoría -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('auditoria.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Buscar Usuario o Descripción:</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Ej: admin o Laptop" value="{{ request('buscar') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Acción:</label>
                    <select name="accion" class="form-select">
                        <option value="">Todas</option>
                        @foreach($acciones as $acc)
                            <option value="{{ $acc }}" {{ request('accion') == $acc ? 'selected' : '' }}>
                                {{ $acc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Módulo:</label>
                    <select name="modulo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($modulos as $mod)
                            <option value="{{ $mod }}" {{ request('modulo') == $mod ? 'selected' : '' }}>
                                {{ $mod }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Desde:</label>
                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Hasta:</label>
                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>

                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100" title="Filtrar">Filtrar</button>
                    @if(request()->hasAny(['buscar', 'accion', 'modulo', 'desde', 'hasta']))
                        <a href="{{ route('auditoria.index') }}" class="btn btn-outline-secondary" title="Limpiar">Limpiar</a>
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
                        <th class="ps-3">Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th class="text-center pe-3">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditorias as $aud)
                        <tr>
                            <td class="ps-3 small text-muted text-nowrap">
                                {{ $aud->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="fw-semibold">
                                {{ $aud->usuario_nombre }}
                            </td>
                            <td>
                                @if(str_contains($aud->accion, 'LOGIN'))
                                    <span class="badge bg-success">{{ $aud->accion }}</span>
                                @elseif(str_contains($aud->accion, 'ELIMINAR'))
                                    <span class="badge bg-danger">{{ $aud->accion }}</span>
                                @elseif(str_contains($aud->accion, 'CREAR'))
                                    <span class="badge bg-primary">{{ $aud->accion }}</span>
                                @elseif(str_contains($aud->accion, 'ACTUALIZAR'))
                                    <span class="badge bg-warning text-dark">{{ $aud->accion }}</span>
                                @elseif(str_contains($aud->accion, 'PERMISO'))
                                    <span class="badge" style="background-color: #6A1B9A;">{{ $aud->accion }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $aud->accion }}</span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $aud->modulo }}</span></td>
                            <td class="small">{{ $aud->descripcion }}</td>
                            <td class="small text-muted font-monospace">{{ $aud->ip_address ?? '127.0.0.1' }}</td>
                            <td class="text-center pe-3">
                                @if($aud->datos_cambio)
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalAuditoria{{ $aud->id }}">
                                        Ver Datos
                                    </button>

                                    <!-- Modal de Datos de Cambio -->
                                    <div class="modal fade" id="modalAuditoria{{ $aud->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fs-6 fw-bold">
                                                        Detalles de Cambio #{{ $aud->id }} &bull; {{ $aud->accion }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body bg-light">
                                                    <pre class="bg-dark text-white p-3 rounded small mb-0" style="max-height: 350px; overflow-y: auto;">{{ json_encode($aud->datos_cambio, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No se encontraron registros de auditoría.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($auditorias->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $auditorias->links() }}
        </div>
    @endif
</div>
@endsection

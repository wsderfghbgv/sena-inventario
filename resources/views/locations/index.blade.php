@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Listado de Ubicaciones</h1>
    @if(Auth::user()?->tienePermiso('locations.crear'))
        <a href="{{ route('locations.create') }}" class="btn btn-primary">+ Nueva Ubicación</a>
    @endif
</div>

<!-- Barra de Búsqueda y Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('locations.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Buscar por Código o Nombre:</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Ej: B01 o Laboratorio" value="{{ request('buscar', request('search')) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tipo:</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        @php
                            $availableTypes = [
                                'bodega' => 'Bodega',
                                'ambiente' => 'Ambiente',
                                'zona' => 'Zona',
                                'compartimento' => 'Compartimento',
                                'mueble' => 'Mueble',
                                'puesto' => 'Puesto',
                                'estante' => 'Estante'
                            ];
                        @endphp
                        @foreach($availableTypes as $val => $label)
                            <option value="{{ $val }}" {{ request('tipo', request('type')) == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Estado:</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ in_array(request('estado', request('status')), ['activo', 'active']) ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ in_array(request('estado', request('status')), ['inactivo', 'inactive']) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    @if(request()->hasAny(['buscar', 'search', 'tipo', 'type', 'estado', 'status']))
                        <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">Limpiar</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead style="background-color: #39A900; color: #fff;">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Unidad</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->code }}</td>
                        <td>
                            <strong>{{ $location->name }}</strong>
                            @if($location->description)
                                <br><small class="text-muted">{{ Str::limit($location->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ ucfirst($location->type) }}</span>
                        </td>
                        <td>{{ $location->parentLocation ? $location->parentLocation->name : 'Principal' }}</td>
                        <td>
                            @if($location->is_active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(Auth::user()?->tienePermiso('locations.ver'))
                                <a href="{{ route('locations.show', $location->id) }}" class="btn btn-sm btn-accion-ver">Ver</a>
                            @endif
                            @if(Auth::user()?->tienePermiso('locations.editar'))
                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-accion-editar">Editar</a>
                            @endif
                            @if(Auth::user()?->tienePermiso('locations.eliminar'))
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta ubicación?');">
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
                            No se encontraron ubicaciones registradas.
                            @if(request()->hasAny(['buscar', 'search', 'tipo', 'type', 'estado', 'status']))
                                <br><a href="{{ route('locations.index') }}" class="btn btn-sm btn-link mt-1">Limpiar filtros</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $locations->links() }}
</div>
@endsection

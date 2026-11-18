@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Listado de Ubicaciones</h2>
    @if(Auth::user()?->tienePermiso('locations.crear'))
        <a href="{{ route('locations.create') }}" class="btn btn-primary">
            + Nueva Ubicación
        </a>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Ubicación </th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td class="fw-bold">{{ $location->code }}</td>
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
                        <td>
                            @if(Auth::user()?->tienePermiso('locations.editar'))
                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                            @else
                                <span class="text-muted small">Solo lectura</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No hay ubicaciones registradas aún. 
                            <a href="{{ route('locations.create') }}">Crear la primera</a>.
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
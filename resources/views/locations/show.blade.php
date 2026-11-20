@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0 fw-bold">Detalles de la Ubicación</h3>
                    @if($location->is_active)
                        <span class="badge bg-success fs-6">Activo</span>
                    @else
                        <span class="badge bg-secondary fs-6">Inactivo</span>
                    @endif
                </div>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <strong>Código:</strong> 
                        <span class="font-monospace fw-bold">{{ $location->code }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <strong>Nombre:</strong> 
                        <span>{{ $location->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <strong>Tipo:</strong> 
                        <span class="badge bg-info text-dark">{{ ucfirst($location->type) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <strong>Pertenece a (Ubicación Padre):</strong> 
                        <span>{{ $location->parentLocation ? $location->parentLocation->name : 'Principal (Ubicación raíz)' }}</span>
                    </li>
                    <li class="list-group-item py-2">
                        <strong>Descripción:</strong>
                        <p class="text-muted mb-0 mt-1">{{ $location->description ?? 'Sin descripción registrada.' }}</p>
                    </li>
                    @if($location->children->count() > 0)
                        <li class="list-group-item py-2">
                            <strong>Sub-ubicaciones contenidas ({{ $location->children->count() }}):</strong>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @foreach($location->children as $child)
                                    <span class="badge bg-light text-dark border">{{ $child->name }} ({{ ucfirst($child->type) }})</span>
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                        Volver al listado
                    </a>

                    @if(Auth::user()?->tienePermiso('locations.editar'))
                        <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-accion-editar">
                            Editar Ubicación
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2>Registrar Nueva Ubicación</h2>
</div>

<form action="{{ route('locations.store') }}" method="POST">
    <!-- Token de seguridad obligatorio en Laravel -->
    @csrf

    <div class="mb-3">
        <label class="form-label">Código:</label>
        <input type="text" name="code" class="form-control" placeholder="Ej: B1-Z2-E3" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="name" class="form-control" placeholder="Ej: Estante Principal A" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo de ubicación:</label>
        <select name="type" class="form-select" required>
            <option value="">Seleccione una opción...</option>
            <option value="bodega">Bodega</option>
            <option value="zona">Zona</option>
            <option value="estante">Estante</option>
            <option value="nivel">Nivel</option>
            <option value="espacio">Espacio</option>
        </select>
    </div>

    <!-- Ubicación padre (Jerarquía) -->
    <div class="mb-3">
        <label class="form-label">Pertenece a (Opcional):</label>
        <select name="parent_id" class="form-select">
            <option value="">Ninguna (Es una bodega principal)</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}">
                    {{ $location->name }} ({{ $location->code }})
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar ubicación</button>
    <a href="{{ route('locations.index') }}" class="btn btn-secondary">Volver</a>
</form>
@endsection
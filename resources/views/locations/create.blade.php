@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2>Registrar Nueva Ubicación</h2>
</div>

<form action="{{ route('locations.store') }}" method="POST">
    
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
    <label for="type" class="form-label">Tipo de Ubicación</label>
    <select name="type" id="type" class="form-select" required>
        <option value="">Seleccione un tipo...</option>
        <option value="ambiente">Ambiente</option>
        <option value="zona">Zona</option>
        <option value="mueble">Mueble / Mesa</option>
        <option value="compartimento">Compartimento / Contenedor</option>
        <option value="puesto">Puesto de Trabajo</option>
    </select>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción (Opcional)</label>
    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Ej: Mesa con dos iMacs frente al mural, o casillero superior izquierdo..."></textarea>
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
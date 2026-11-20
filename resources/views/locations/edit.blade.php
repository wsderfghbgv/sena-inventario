@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Ubicación</h2>
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('locations.update', $location->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Código -->
                <div class="mb-3">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                           value="{{ old('code', $location->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $location->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipo -->
                <div class="mb-3">
                    <label for="type" class="form-label">Tipo de Ubicación</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Seleccione un tipo...</option>
                        <option value="ambiente" {{ old('type', $location->type) == 'ambiente' ? 'selected' : '' }}>Ambiente</option>
                        <option value="zona" {{ old('type', $location->type) == 'zona' ? 'selected' : '' }}>Zona</option>
                        <option value="mueble" {{ old('type', $location->type) == 'mueble' ? 'selected' : '' }}>Mueble / Mesa</option>
                        <option value="compartimento" {{ old('type', $location->type) == 'compartimento' ? 'selected' : '' }}>Compartimento / Contenedor</option>
                        <option value="puesto" {{ old('type', $location->type) == 'puesto' ? 'selected' : '' }}>Puesto de Trabajo</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ubicación Padre -->
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Ubicación Padre (Opcional)</label>
                    <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">Ninguna (Ubicación Principal)</option>
                        @foreach($locations as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $location->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción (Opcional)</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3">{{ old('description', $location->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Ubicación</button>
            </form>
        </div>
    </div>
</div>
@endsection

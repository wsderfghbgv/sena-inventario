@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4">Editar Producto</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Código Único (No modificable):</label>
                        <input type="text" class="form-control" value="{{ $producto->codigo }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría:</label>
                        <select name="categoria" id="categoria" class="form-select" required>
                            <option value="">-- Seleccione una categoría --</option>
                            @php
                                $categorias = [
                                    'Tecnología y Cómputo',
                                    'Periféricos y Conectividad',
                                    'Electrónica y Robótica',
                                    'Herramientas',
                                    'Equipos de Medición',
                                    'Materiales e Insumos',
                                    'Elementos de Protección (EPP)',
                                    'Mobiliario y Enseres',
                                    'Papelería y Oficina',
                                    'Otros'
                                ];
                                $catSeleccionada = old('categoria', $producto->categoria);
                            @endphp
                            @if(!in_array($catSeleccionada, $categorias) && !empty($catSeleccionada))
                                <option value="{{ $catSeleccionada }}" selected>{{ $catSeleccionada }}</option>
                            @endif
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ $catSeleccionada == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unidad de Medida (Opcional):</label>
                        <input type="text" name="unidad" class="form-control" value="{{ old('unidad', $producto->unidad) }}">
                        <small class="text-muted">Si se deja vacío, se guardará como 'Unidad'.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado:</label>
                        <select name="estado" class="form-control" required>
                            <option value="activo" {{ $producto->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ $producto->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Listado de Productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
</div>

<!-- RF-002: Barra de Búsqueda y Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('productos.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Buscar por Código o Nombre:</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Ej: PROD-001 o Laptop" value="{{ request('buscar') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Categoría:</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Estado:</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    @if(request()->hasAny(['buscar', 'categoria', 'estado']))
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">Limpiar</a>
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
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria }}</td>
                        <td>{{ $producto->unidad }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>
                            @if($producto->estado == 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm btn-info text-white">Ver</a>
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No se encontraron productos registrados.
                            @if(request()->hasAny(['buscar', 'categoria', 'estado']))
                                <br><a href="{{ route('productos.index') }}" class="btn btn-sm btn-link mt-1">Limpiar filtros</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

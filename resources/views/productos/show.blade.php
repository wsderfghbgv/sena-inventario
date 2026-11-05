@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4">Detalles del Producto</h3>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>Código:</strong> {{ $producto->codigo }}</li>
                    <li class="list-group-item"><strong>Nombre:</strong> {{ $producto->nombre }}</li>
                    <li class="list-group-item"><strong>Descripción:</strong> {{ $producto->descripcion ?? 'Sin descripción' }}</li>
                    <li class="list-group-item"><strong>Categoría:</strong> {{ $producto->categoria }}</li>
                    <li class="list-group-item"><strong>Unidad:</strong> {{ $producto->unidad }}</li>
                    <li class="list-group-item"><strong>Stock:</strong> {{ $producto->stock }}</li>
                    <li class="list-group-item"><strong>Estado:</strong> {{ ucfirst($producto->estado) }}</li>
                </ul>

                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver al listado</a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h2 class="fw-bold mb-2">Registrar entrada</h2>
                <p class="text-muted mb-4">
                    Registra el ingreso de productos al inventario SENNOVA.
                </p>

                <form action="{{ route('entradas.store') }}" method="POST">
                    @csrf

                    <h5 class="fw-bold mb-3">Información general</h5>

                    <div class="mb-3">
                        <label class="form-label">Número de entrada</label>
                        <input type="text" name="numero"
                            class="form-control"
                            value="{{ old('numero') }}"
                            placeholder="Ejemplo: E001"
                            required>
                        @error('numero')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de entrada</label>
                            <input type="date" name="fecha"
                                class="form-control"
                                value="{{ old('fecha') }}"
                                required>
                            @error('fecha')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora de entrada</label>
                            <input type="time" name="hora"
                                class="form-control"
                                value="{{ old('hora') }}"
                                required>
                            @error('hora')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ubicación</label>
                        <select name="ubicacion_id" class="form-select" required>
                            <option value="">Seleccione una ubicación</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id }}"
                                    {{ old('ubicacion_id') == $ubicacion->id ? 'selected' : '' }}>
                                    {{ $ubicacion->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ubicacion_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Productos de la entrada</h5>

                        <button type="button" class="btn btn-outline-success btn-sm"
                            id="agregarProducto">
                            + Agregar producto
                        </button>
                    </div>

                    <div id="contenedorProductos">

                        <div class="row fila-producto align-items-end mb-3">

                            <div class="col-md-7">
                                <label class="form-label">Producto</label>
                                <select name="detalles[0][producto_id]"
                                    class="form-select" required>
                                    <option value="">Seleccione un producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}">
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number"
                                    name="detalles[0][cantidad]"
                                    class="form-control"
                                    min="1"
                                    step="1"
                                    placeholder="Cantidad"
                                    required>
                            </div>

                            <div class="col-md-2">
                                <button type="button"
                                    class="btn btn-outline-danger w-100 eliminarProducto"
                                    disabled>
                                    Quitar
                                </button>
                            </div>

                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones"
                            class="form-control"
                            rows="3"
                            placeholder="Observaciones adicionales">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Guardar entrada
                        </button>

                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const contenedor = document.getElementById('contenedorProductos');
    const botonAgregar = document.getElementById('agregarProducto');

    function actualizarFilas() {
        const filas = contenedor.querySelectorAll('.fila-producto');

        filas.forEach((fila, index) => {
            fila.querySelector('select').name =
                `detalles[${index}][producto_id]`;

            fila.querySelector('input').name =
                `detalles[${index}][cantidad]`;

            fila.querySelector('.eliminarProducto').disabled =
                filas.length === 1;
        });
    }

    botonAgregar.addEventListener('click', function () {

        const primeraFila = contenedor.querySelector('.fila-producto');
        const nuevaFila = primeraFila.cloneNode(true);

        nuevaFila.querySelector('select').selectedIndex = 0;
        nuevaFila.querySelector('input').value = '';

        contenedor.appendChild(nuevaFila);

        actualizarFilas();
    });

    contenedor.addEventListener('click', function (event) {

        if (event.target.classList.contains('eliminarProducto')) {
            const filas = contenedor.querySelectorAll('.fila-producto');

            if (filas.length > 1) {
                event.target.closest('.fila-producto').remove();
                actualizarFilas();
            }
        }
    });

    actualizarFilas();
});
</script>
@endsection

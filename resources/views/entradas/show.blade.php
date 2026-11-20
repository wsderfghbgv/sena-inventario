@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Detalle de entrada</h1>
            <p class="text-muted mb-0">
                Información de la entrada {{ $entrada->numero }}
            </p>
        </div>

        <a href="{{ route('entradas.index') }}" class="btn btn-outline-secondary">
            Volver al listado
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Número de entrada:</strong>
                    <p>{{ $entrada->numero }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Fecha:</strong>
                    <p>{{ $entrada->fecha?->format('d/m/Y') }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Ubicación:</strong>
                    <p>{{ $entrada->ubicacion->name ?? 'Sin ubicación' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Usuario:</strong>
                    <p>{{ $entrada->usuario->name ?? 'Sin usuario' }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Estado:</strong>
                    <p>
                        @if ($entrada->estado === 'confirmada')
                            <span class="badge bg-success">Confirmada</span>
                        @else
                            <span class="badge bg-warning text-dark">Borrador</span>
                        @endif
                    </p>
                </div>

                <div class="col-12">
                    <strong>Observaciones:</strong>
                    <p>{{ $entrada->observaciones ?? 'Sin observaciones' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Productos de la entrada</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th class="text-center">Cantidad</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($entrada->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</td>
                                <td>{{ $detalle->producto->codigo ?? '—' }}</td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Esta entrada no tiene productos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($entrada->estado === 'borrador' && $entrada->detalles->isNotEmpty())
        <form action="{{ route('entradas.confirmar', $entrada) }}"
              method="POST"
              class="mt-4"
              onsubmit="return confirm('¿Estás segura de que deseas confirmar esta entrada?');">
            @csrf
            @method('PATCH')

            <button type="submit" class="btn btn-success">
                Confirmar entrada
            </button>
        </form>
    @endif

</div>
@endsection

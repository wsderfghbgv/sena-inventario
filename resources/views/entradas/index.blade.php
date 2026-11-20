@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Entradas</h1>
            <p class="text-muted mb-0">Consulta y administra las entradas de inventario.</p>
        </div>

        <a href="{{ route('entradas.create') }}" class="btn btn-primary">
            + Registrar entrada
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Ubicación</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($entradas as $entrada)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $entrada->numero }}
                                </td>

                                <td>
                                    {{ $entrada->fecha?->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $entrada->ubicacion->name ?? 'Sin ubicación' }}
                                </td>

                                <td>
                                    {{ $entrada->usuario->name ?? 'Sin usuario' }}
                                </td>

                                <td>
                                    @if ($entrada->estado === 'confirmada')
                                        <span class="badge bg-success">Confirmada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Borrador</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('entradas.show', $entrada) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay entradas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $entradas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

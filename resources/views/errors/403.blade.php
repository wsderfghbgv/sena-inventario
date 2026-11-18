@extends('layouts.app')

@section('content')
<div class="row justify-content-center text-center py-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 p-5">
            <div class="mb-4">
                <span class="badge rounded-pill bg-danger px-3 py-2 fs-6">Error 403 - Acceso Denegado</span>
            </div>
            <h2 class="fw-bold mb-3" style="color: #39A900;">Acceso Restringido</h2>
            <p class="text-muted mb-4">
                {{ $exception->getMessage() ?: 'No tienes los permisos necesarios para acceder a este módulo del sistema de inventario.' }}
            </p>
            <div>
                <a href="{{ route('productos.index') }}" class="btn btn-primary">
                    Regresar al Inicio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

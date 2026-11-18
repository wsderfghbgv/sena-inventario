@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="fw-bold mb-0">Registrar Usuario</h3>
                </div>
                <p class="text-muted small">Cree una cuenta y vincule el rol desde la tabla de roles (RF-021).</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold">Nombre Completo:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Ej: Pepito Pérez" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">Correo Institucional:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="ejemplo@sena.edu.co" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">Contraseña Inicial:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label for="rol_id" class="form-label small fw-bold">Rol Asignado (Tabla Roles):</label>
                            <select name="rol_id" id="rol_id" class="form-select" required>
                                <option value="">-- Seleccionar Rol --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" {{ old('rol_id') == $r->id ? 'selected' : '' }}>
                                        {{ ucfirst($r->nombre) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Define los permisos en rutas protegidas.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="estado" class="form-label small fw-bold">Estado de la Cuenta:</label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="activo" {{ old('estado', 'activo') === 'activo' ? 'selected' : '' }}>Activo (Habilitado)</option>
                                <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo (Bloqueado)</option>
                            </select>
                            <small class="text-muted d-block mt-1">Solo usuarios activos pueden autenticarse.</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

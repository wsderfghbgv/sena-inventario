@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="fw-bold mb-0">Editar Usuario y Rol</h3>
                </div>
                <p class="text-muted small">Actualice información, rol asignado o estado de acceso (RF-021).</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold">Nombre Completo:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">Correo Institucional:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="rol" class="form-label small fw-bold">Rol Asignado:</label>
                            <select name="rol" id="rol" class="form-select" required {{ Auth::id() === $usuario->id ? 'readonly' : '' }}>
                                <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="instructor" {{ old('rol', $usuario->rol) === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                <option value="aprendiz" {{ old('rol', $usuario->rol) === 'aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                            </select>
                            @if(Auth::id() === $usuario->id)
                                <small class="text-muted d-block mt-1">No puedes cambiar tu propio rol.</small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="estado" class="form-label small fw-bold">Estado:</label>
                            <select name="estado" id="estado" class="form-select" required {{ Auth::id() === $usuario->id ? 'readonly' : '' }}>
                                <option value="activo" {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $usuario->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @if(Auth::id() === $usuario->id)
                                <small class="text-muted d-block mt-1">No puedes desactivarte a ti mismo.</small>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label small fw-bold">Nueva Contraseña (Opcional):</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

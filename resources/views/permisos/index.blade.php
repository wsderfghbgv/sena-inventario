@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Gestión de Permisos por Rol</h2>
        <p class="text-muted small mb-0">Configuración granular del control de acceso en servidor (RF-022)</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('permisos.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="alert alert-info py-2 small mb-4">
                <strong>Información de Seguridad:</strong> El rol <strong>Administrador</strong> siempre cuenta con acceso absoluto por diseño. Los roles <strong>Instructor</strong> y <strong>Aprendiz</strong> pueden ser configurados marcando o desmarcando los permisos requeridos.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead style="background-color: var(--sena-verde-oscuro); color: #fff;">
                        <tr>
                            <th style="width: 20%;">Módulo</th>
                            <th style="width: 35%;">Permiso / Acción</th>
                            @foreach($roles as $role)
                                <th class="text-center" style="width: 15%;">
                                    {{ ucfirst($role->nombre) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisosPorModulo as $modulo => $permisos)
                            @foreach($permisos as $index => $permiso)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ count($permisos) }}" class="fw-bold bg-light align-top pt-3">
                                            {{ $modulo }}
                                        </td>
                                    @endif
                                    <td>
                                        <span class="fw-semibold">{{ $permiso->descripcion }}</span>
                                        <br>
                                        <code class="text-muted small">{{ $permiso->nombre }}</code>
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="text-center">
                                            @if($role->nombre === 'admin')
                                                <input type="checkbox" class="form-check-input" checked disabled title="Acceso total concedido por defecto">
                                            @else
                                                <input type="checkbox" 
                                                       name="permisos[{{ $role->id }}][]" 
                                                       value="{{ $permiso->id }}"
                                                       class="form-check-input"
                                                       {{ $role->permisos->contains('id', $permiso->id) ? 'checked' : '' }}>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    Guardar y Aplicar Permisos
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

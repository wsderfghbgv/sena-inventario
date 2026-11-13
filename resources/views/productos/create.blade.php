@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4">Registrar Producto</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Código Único:</label>
                        <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion') }}</textarea>
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
                            @endphp
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unidad de Medida:</label>
                        <input type="text" name="unidad" class="form-control" placeholder="Ej: Unidades, Litros" value="{{ old('unidad') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
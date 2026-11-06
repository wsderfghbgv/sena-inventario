



<form method="POST" action="{{ route('locations.store') }}">
    @csrf

    <div>
        <label>Código de la ubicación:</label>
        <input type="text" name="code" required>
    </div>

    <div>
        <label>Nombre:</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Tipo de ubicación:</label>
        <select name="type" required>
            <option value="">Seleccione una opción...</option>
            <option value="bodega">Bodega</option>
            <option value="zona">Zona</option>
            <option value="estante">Estante</option>
            <option value="nivel">Nivel</option>
            <option value="espacio">Espacio</option>
        </select>
    </div>

    <!-- campo para la jerarquía (ubicación padre) -->
    <div>
        <label>Pertenece a (Opcional):</label>
        <select name="parent_id">
            <option value="">Ninguna (Es una bodega principal)</option>

            <!-- Recorremos las ubicaciones que nos mandó el compact -->
            @foreach($locations as $location)
                <option value="{{ $location->id }}">
                    {{ $location->name }} ({{ $location->code }})
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit">Guardar ubicación</button>
</form>
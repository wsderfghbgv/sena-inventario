



<form method="POST" action="{{ route('locations.store'}}">
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
        <label>Tipo de ubicacion</label>
        <select name="name" required>
            <option value=""> Seleccione una opcion...</option>
            <option value="bodega">Bodega</option>
            <option value="zona">Zona</option>
            <option value="estante">Estante</option>
            <option value="nivel">Nivel</option>
            <option value="espacio">Espacio</option>
        </select>
    </div>

    <button type="submit">Guardar ubicacion</button>


</form>
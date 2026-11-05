<?php

use App\Http\Controllers\ProductosController;

Route::resource('productos', ProductosController::class);

// Redirigir la raíz al listado de productos
Route::get('/', function () {
    return redirect()->route('productos.index');
});

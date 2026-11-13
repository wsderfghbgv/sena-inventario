<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductosController;

// Redirigir la raíz al listado de productos
Route::get('/', function () {
    return redirect()->route('productos.index');
});

Route::resource('productos', ProductosController::class);
Route::resource('locations', LocationController::class);

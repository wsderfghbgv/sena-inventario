<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\AuthController;

// Redirigir la raíz al listado de productos
Route::get('/', function () {
    return redirect()->route('productos.index');
});

Route::resource('productos', ProductosController::class);
Route::resource('locations', LocationController::class);

Route::get('/entradas/crear', [EntradaController::class, 'create'])
    ->name('entradas.create');
Route::post('/entradas', [EntradaController::class, 'store'])
    ->name('entradas.store');

Route::get('/entradas', [EntradaController::class, 'index'])
    ->name('entradas.index');

Route::get('/entrada/{entrada}',[EntradaController::class, 'show'])
    ->name('entradas.show'); 

Route::patch('entrada/{entrada}/confirmar',[EntradaController::class, 'confirmar'])
    ->name('entradas.confirmar'); 


Route::get('/login', [AuthController::class, 'showLogin'])  //muestra el formulario
    ->name('login');
Route::post('/login', [AuthController::class, 'login']) //válida einicia sesión
    ->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']) // cierra sesión
    ->name('logout');


Route::get('/registro', [AuthController::class, 'showRegister'])
    ->name('register');
Route::post('/registro', [AuthController::class, 'register'])
    ->name('register.post');


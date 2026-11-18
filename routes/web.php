<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Autenticación - RF-020)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Redirección de raíz
Route::get('/', function () {
    return Auth::check() ? redirect()->route('productos.index') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren inicio de sesión y usuario activo - RF-020 / RF-021)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role'])->group(function () {

    // Cierre de sesión (RF-020)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas operativas de inventario (Admin e Instructor pueden crear y modificar)
    Route::middleware('role:admin,instructor')->group(function () {
        Route::get('/productos/create', [ProductosController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductosController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/edit', [ProductosController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductosController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductosController::class, 'destroy'])->name('productos.destroy');

        Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
        Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
        Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    });

    // Módulos de consulta accesibles para todos los roles autenticados (Admin, Instructor, Aprendiz)
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/productos/{producto}', [ProductosController::class, 'show'])->name('productos.show');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');

    // Gestión de Usuarios y Roles (Exclusivo Administrador - RF-021)
    Route::middleware('role:admin')->group(function () {
        Route::resource('usuarios', UserController::class)->except(['show']);
    });
});

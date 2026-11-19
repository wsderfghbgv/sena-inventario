<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntradaController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Redirección de raíz
Route::get('/', function () {
    return Auth::check() ? redirect()->route('productos.index') : redirect()->route('login');
});


Route::middleware(['auth', 'role'])->group(function () {

    // Cierre de sesión (RF-020)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Operaciones de creación, edición y eliminación de inventario (Protegidas por Permisos - RF-022)
    Route::middleware('permission:productos.crear')->group(function () {
        Route::get('/productos/create', [ProductosController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductosController::class, 'store'])->name('productos.store');
    });

    Route::middleware('permission:productos.editar')->group(function () {
        Route::get('/productos/{producto}/edit', [ProductosController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductosController::class, 'update'])->name('productos.update');
    });

    Route::middleware('permission:productos.eliminar')->group(function () {
        Route::delete('/productos/{producto}', [ProductosController::class, 'destroy'])->name('productos.destroy');
    });

    // Ubicaciones protegidas por permisos (RF-022)
    Route::middleware('permission:locations.crear')->group(function () {
        Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    });

    Route::middleware('permission:locations.editar')->group(function () {
        Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
        Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    });

    Route::middleware('permission:locations.eliminar')->group(function () {
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    });

    // Módulos de consulta accesibles con permiso de lectura (RF-022)
    Route::middleware('permission:productos.ver')->group(function () {
        Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
        Route::get('/productos/{producto}', [ProductosController::class, 'show'])->name('productos.show');
    });

    Route::middleware('permission:locations.ver')->group(function () {
        Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
        Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
    });

    // Gestión de Usuarios y Roles (Exclusivo Administrador - RF-021)
    Route::middleware(['role:admin', 'permission:usuarios.gestionar'])->group(function () {
        Route::resource('usuarios', UserController::class)->except(['show']);
    });

    // Gestión y Modificación de Permisos en Servidor (Exclusivo Administrador - RF-022)
    Route::middleware(['role:admin', 'permission:permisos.modificar'])->group(function () {
        Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
        Route::put('/permisos', [PermisoController::class, 'update'])->name('permisos.update');
    });

    // Consulta de Auditoría Inmutable (Exclusivo Administrador - RF-023)
    Route::middleware(['role:admin', 'permission:auditoria.consultar'])->group(function () {
        Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    });

    Route::get('/entradas/crear', [EntradaController::class, 'create'])->name('entradas.create');
    Route::post('/entradas', [EntradaController::class, 'store'])->name('entradas.store');
});

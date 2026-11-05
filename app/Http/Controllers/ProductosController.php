<?php

namespace App\Http\Controllers;

use App\Models\productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    // RF-002: Mostrar listado de productos
    public function index()
    {
        $productos = productos::all();
        return view('productos.index', compact('productos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('productos.create');
    }

    // RF-001: Guardar nuevo producto (sin alterar existencias)
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
        ]);

        productos::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'unidad' => $request->unidad,
            'stock' => 0,
            'estado' => 'activo'
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto registrado exitosamente.');
    }

    // RF-002: Mostrar detalle de un producto
    public function show($id)
    {
        $producto = productos::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $producto = productos::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    // RF-003 y RF-004: Modificar datos y cambiar estado conservando historial
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'unidad' => 'required|string|max:50',
            'estado' => 'required|string|in:activo,inactivo',
        ]);

        $producto = productos::findOrFail($id);
        
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'unidad' => $request->unidad,
            'estado' => $request->estado,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    // Eliminar producto
    public function destroy($id)
    {
        $producto = productos::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
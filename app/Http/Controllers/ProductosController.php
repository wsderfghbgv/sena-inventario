<?php

namespace App\Http\Controllers;

use App\Models\productos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    // RF-002: Mostrar listado de productos con búsqueda y filtros
    public function index(Request $request)
    {
        $query = productos::query();

        // Filtro por texto: código o nombre
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('created_at', 'desc')->get();

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

        return view('productos.index', compact('productos', 'categorias'));
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
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string|max:255',
            'unidad' => 'nullable|string|max:50',
            'estado' => 'required|string|in:activo,inactivo',
        ]);

        productos::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'unidad' => $request->filled('unidad') ? $request->unidad : 'Unidad',
            'stock' => 0,
            'estado' => $request->estado ?? 'activo',
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
            'descripcion' => 'nullable|string',
            'categoria' => 'required|string|max:255',
            'unidad' => 'nullable|string|max:50',
            'estado' => 'required|string|in:activo,inactivo',
        ]);

        $producto = productos::findOrFail($id);
        
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'unidad' => $request->filled('unidad') ? $request->unidad : 'Unidad',
            'estado' => $request->estado,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    // RF-004: Eliminar producto asegurando no borrar registros con historial
    public function destroy($id)
    {
        $producto = productos::findOrFail($id);

        try {
            $producto->delete();
            return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (QueryException $e) {
            return redirect()->route('productos.index')->with(
                'error',
                "No se puede eliminar el producto '{$producto->nombre}' porque tiene movimientos o registros asociados en el sistema. En su lugar, puede inactivarlo desde la opción Editar."
            );
        }
    }
}

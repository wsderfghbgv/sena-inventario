<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Location;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    public function create()
    {
        $ubicaciones = Location::where('is_active', true)
            ->orderBy('name')
            ->get();

        $productos = productos::activos()
            ->orderBy('nombre')
            ->get();

        return view('entradas.create', compact(
            'ubicaciones',
            'productos'
        ));
    }

    public function store(Request $request)
    {
        // Verificar que haya un usuario autenticado
        if (!auth()->check()) {
            return back()->with('error',
                'Debes iniciar sesión para registrar una entrada.'
            )->withInput();
        }

        // Validar los datos recibidos
        $datos = $request->validate([
            'numero' => 'required|string|max:255|unique:entradas,numero',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'ubicacion_id' => 'required|exists:locations,id',
            'observaciones' => 'nullable|string',

            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|distinct|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
        ], [
            'numero.required' => 'El número de entrada es obligatorio.',
            'numero.unique' => 'Este número de entrada ya existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'hora.required' => 'La hora es obligatoria.',
            'ubicacion_id.required' => 'Debes seleccionar una ubicación.',
            'detalles.required' => 'Debes agregar al menos un producto.',
            'detalles.*.producto_id.required' => 'Selecciona un producto.',
            'detalles.*.producto_id.distinct' => 'No puedes repetir un producto en la misma entrada.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'detalles.*.cantidad.min' => 'La cantidad debe ser mayor que cero.',
        ]);

        // Guardar cabecera y detalles en una sola transacción
        DB::transaction(function () use ($datos) {

            $entrada = Entrada::create([
                'numero' => $datos['numero'],
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'usuario_id' => auth()->id(),
                'ubicacion_id' => $datos['ubicacion_id'],
                'observaciones' => $datos['observaciones'] ?? null,
                'estado' => 'borrador',
            ]);

            foreach ($datos['detalles'] as $detalle) {
                $entrada->detalles()->create([
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                ]);
            }
        });

        return redirect()
            ->route('entradas.create')
            ->with('success', 'La entrada se registró correctamente.');
    }
}

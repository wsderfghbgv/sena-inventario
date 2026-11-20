<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Location;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        if (!auth()->check()) {
            return back()->with('error',
                'Debes iniciar sesión para registrar una entrada.'
            )->withInput();
        }

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
            'numero.unique' => 'Este número de entrada ya está registrado. Por favor, ingresa uno diferente.',
            'numero.max' => 'El número de entrada no puede superar los 255 caracteres.',

            'fecha.required' => 'La fecha de entrada es obligatoria.',
            'fecha.date' => 'Ingresa una fecha válida.',

            'hora.required' => 'La hora de entrada es obligatoria.',
            'hora.date_format' => 'Ingresa una hora válida.',

            'ubicacion_id.required' => 'Debes seleccionar una ubicación.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no es válida.',

            'detalles.required' => 'Debes agregar al menos un producto.',
            'detalles.min' => 'Debes agregar al menos un producto.',

            'detalles.*.producto_id.required' => 'Debes seleccionar un producto.',
            'detalles.*.producto_id.distinct' => 'No puedes repetir un producto en la misma entrada.',
            'detalles.*.producto_id.exists' => 'Uno de los productos seleccionados no existe.',

            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'detalles.*.cantidad.min' => 'La cantidad debe ser de al menos 1 unidad.',
        ]);

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
            ->route('entradas.index')
            ->with('success', 'Entrada registrada correctamente.');
    }

    public function index()
    {
        $entradas = Entrada::with([
            'usuario',
            'ubicacion',
            'detalles.producto'
        ])
            ->orderByDesc('id')
            ->paginate(10);

        return view('entradas.index', compact('entradas'));
    }

    public function show(Entrada $entrada)
    {
        $entrada->load([    
            'usuario',
            'ubicacion',
            'detalles.producto'
        ]);

        return view('entradas.show', compact('entrada'));
    }

    public function confirmar(Entrada $entrada)
    {
        DB::transaction(function () use ($entrada) {

            $entrada = Entrada::whereKey($entrada->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($entrada->estado !== 'borrador') {
                throw ValidationException::withMessages([
                    'estado' => 'Esta entrada ya fue confirmada o no puede confirmarse.',
                ]);
            }

            $detalles = $entrada->detalles()->get();

            if ($detalles->isEmpty()) {
                throw ValidationException::withMessages([
                    'detalles' => 'No puedes confirmar una entrada sin productos.',
                ]);
            }

            foreach ($detalles as $detalle) {
                $producto = productos::whereKey($detalle->producto_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $producto->increment('stock', $detalle->cantidad);
            }

            $entrada->update([
                'estado' => 'confirmada',
            ]);
        });

        return redirect()
            ->route('entradas.show', $entrada)
            ->with('success', 'Entrada confirmada y existencias actualizadas.');
    }
}

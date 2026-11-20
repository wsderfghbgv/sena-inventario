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
        $entradas = Entrada::with(['usuario', 'ubicacion'])
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

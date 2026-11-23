<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::with(['parentLocation', 'children']);

        // Filtro por término de búsqueda (código, nombre o descripción)
        $search = $request->input('buscar', $request->input('search'));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por Tipo de ubicación
        $type = $request->input('tipo', $request->input('type'));
        if (!empty($type) && $type !== 'all') {
            $query->where('type', $type);
        }

        // Filtro por Estado (activo/inactivo)
        $status = $request->input('estado', $request->input('status'));
        if (!empty($status) && $status !== 'all') {
            $isActive = in_array($status, ['activo', 'active', '1', 1], true);
            $query->where('is_active', $isActive);
        }

        $locations = $query->latest()->paginate(10)->withQueryString();
        $totalActive = Location::where('is_active', true)->count();
        $allTypes = Location::select('type')->distinct()->pluck('type');

        return view('locations.index', compact('locations', 'totalActive', 'allTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::all();
        return view('locations.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidos = $request->validate([
            'code' => 'required|unique:locations,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:ambiente,zona,mueble,compartimento,puesto',
            'parent_id' => 'nullable|exists:locations,id',
        ]); 

        Location::create($datosValidos);

        return redirect()->route('locations.index')->with('success', 'Ubicacion registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::with(['parentLocation', 'children'])->findOrFail($id);
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $location = Location::findOrFail($id);
        $locations = Location::where('id', '!=', $id)->get();

        return view('locations.edit', compact('location', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::findOrFail($id);

        $datosValidos = $request->validate([
            'code' => 'required|unique:locations,code,' . $location->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:ambiente,zona,mueble,compartimento,puesto',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        $location->update($datosValidos);
        return redirect()->route('locations.index')->with('success', 'Ubicación actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $location = Location::findOrFail($id);

    // Verificar si tiene sub-ubicaciones
    $tieneHijos = Location::where('parent_id', $location->id)->exists();

    if ($tieneHijos) {
        return redirect()->route('locations.index')
            ->with('error', 'No se puede eliminar porque tiene sub-ubicaciones asignadas.');
    }

    // Verificar si tiene entradas asociadas
    $tieneEntradas = DB::table('entradas')
        ->where('ubicacion_id', $location->id)
        ->exists();

    if ($tieneEntradas) {
        return redirect()->route('locations.index')
            ->with('error', 'No se puede eliminar porque tiene entradas asociadas.');
    }

    // Eliminar ubicación
    $location->delete();

    return redirect()->route('locations.index')
        ->with('success', 'Ubicación eliminada correctamente');
}
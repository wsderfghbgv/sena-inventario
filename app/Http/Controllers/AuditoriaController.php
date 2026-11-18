<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    /**
     * Muestra el historial inmutable de acciones críticas (RF-023).
     */
    public function index(Request $request)
    {
        $query = Auditoria::with('user');

        // Filtro por término (usuario o descripción)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('usuario_nombre', 'ILIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'ILIKE', "%{$buscar}%");
            });
        }

        // Filtro por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Filtro por módulo
        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        // Filtro por fecha desde
        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $auditorias = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $acciones = Auditoria::select('accion')->distinct()->pluck('accion');
        $modulos = Auditoria::select('modulo')->distinct()->pluck('modulo');

        return view('auditoria.index', compact('auditorias', 'acciones', 'modulos'));
    }
}

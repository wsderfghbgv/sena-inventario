<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Permiso;
use App\Models\Role;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Muestra la matriz de permisos por rol (RF-022).
     */
    public function index()
    {
        $roles = Role::with('permisos')->orderBy('id')->get();
        $permisosPorModulo = Permiso::all()->groupBy('modulo');

        return view('permisos.index', compact('roles', 'permisosPorModulo'));
    }

    /**
     * Guarda y aplica las modificaciones de permisos en el servidor (RF-022 / RF-023).
     */
    public function update(Request $request)
    {
        $permisosPorRol = $request->input('permisos', []);
        $roles = Role::all();
        $cambiosAuditados = [];

        foreach ($roles as $role) {
            // El administrador siempre conserva todos los permisos
            if ($role->nombre === Role::ADMIN) {
                $todosLosPermisos = Permiso::pluck('id')->toArray();
                $role->permisos()->sync($todosLosPermisos);
                continue;
            }

            $permisosSeleccionados = $permisosPorRol[$role->id] ?? [];
            $permisosPrevios = $role->permisos->pluck('nombre')->toArray();

            // Sincronizar en base de datos
            $role->permisos()->sync($permisosSeleccionados);

            $nuevosPermisos = Permiso::whereIn('id', $permisosSeleccionados)->pluck('nombre')->toArray();
            $cambiosAuditados[$role->nombre] = [
                'anteriores' => $permisosPrevios,
                'nuevos' => $nuevosPermisos,
            ];
        }

        // RF-023: Registro de acción crítica en auditoría
        Auditoria::registrar(
            'MODIFICAR_PERMISOS',
            'Seguridad',
            'Se actualizó la matriz de permisos para los roles del sistema.',
            $cambiosAuditados
        );

        return redirect()->route('permisos.index')
            ->with('success', 'Permisos actualizados y aplicados correctamente en el servidor.');
    }
}

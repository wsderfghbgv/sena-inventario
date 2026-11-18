<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            // Productos
            ['nombre' => 'productos.ver', 'descripcion' => 'Consultar productos y detalles', 'modulo' => 'Productos'],
            ['nombre' => 'productos.crear', 'descripcion' => 'Registrar nuevos productos', 'modulo' => 'Productos'],
            ['nombre' => 'productos.editar', 'descripcion' => 'Modificar información de productos', 'modulo' => 'Productos'],
            ['nombre' => 'productos.eliminar', 'descripcion' => 'Eliminar productos del inventario', 'modulo' => 'Productos'],

            // Ubicaciones
            ['nombre' => 'locations.ver', 'descripcion' => 'Consultar listado de ubicaciones', 'modulo' => 'Ubicaciones'],
            ['nombre' => 'locations.crear', 'descripcion' => 'Crear nuevas ubicaciones', 'modulo' => 'Ubicaciones'],
            ['nombre' => 'locations.editar', 'descripcion' => 'Modificar datos de ubicaciones', 'modulo' => 'Ubicaciones'],
            ['nombre' => 'locations.eliminar', 'descripcion' => 'Eliminar ubicaciones', 'modulo' => 'Ubicaciones'],

            // Seguridad y Auditoría
            ['nombre' => 'usuarios.gestionar', 'descripcion' => 'Administrar usuarios y roles', 'modulo' => 'Usuarios'],
            ['nombre' => 'permisos.modificar', 'descripcion' => 'Modificar permisos asignados a roles', 'modulo' => 'Seguridad'],
            ['nombre' => 'auditoria.consultar', 'descripcion' => 'Consultar historial inmutable de auditoría', 'modulo' => 'Auditoría'],
        ];

        $permisosCreados = [];
        foreach ($permisos as $p) {
            $permisosCreados[$p['nombre']] = Permiso::firstOrCreate(['nombre' => $p['nombre']], $p);
        }

        // Asignación de permisos a roles (RF-022)
        $admin = Role::where('nombre', Role::ADMIN)->first();
        $instructor = Role::where('nombre', Role::INSTRUCTOR)->first();
        $aprendiz = Role::where('nombre', Role::APRENDIZ)->first();

        if ($admin) {
            $admin->permisos()->sync(collect($permisosCreados)->pluck('id'));
        }

        if ($instructor) {
            $instructorPermisos = [
                $permisosCreados['productos.ver']->id,
                $permisosCreados['productos.crear']->id,
                $permisosCreados['productos.editar']->id,
                $permisosCreados['locations.ver']->id,
                $permisosCreados['locations.crear']->id,
                $permisosCreados['locations.editar']->id,
            ];
            $instructor->permisos()->sync($instructorPermisos);
        }

        if ($aprendiz) {
            $aprendizPermisos = [
                $permisosCreados['productos.ver']->id,
                $permisosCreados['locations.ver']->id,
            ];
            $aprendiz->permisos()->sync($aprendizPermisos);
        }
    }
}

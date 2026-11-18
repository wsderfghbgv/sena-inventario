<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => Role::ADMIN,
                'descripcion' => 'Administrador con acceso total y gestión de usuarios',
            ],
            [
                'nombre' => Role::INSTRUCTOR,
                'descripcion' => 'Instructor SENNOVA con permisos para registrar y gestionar inventario',
            ],
            [
                'nombre' => Role::APRENDIZ,
                'descripcion' => 'Aprendiz SENA con permisos de consulta y lectura',
            ],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['nombre' => $r['nombre']], $r);
        }
    }
}

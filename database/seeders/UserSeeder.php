<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('nombre', Role::ADMIN)->first();
        $instructorRole = Role::where('nombre', Role::INSTRUCTOR)->first();
        $aprendizRole = Role::where('nombre', Role::APRENDIZ)->first();

        // 1. Administrador (RF-020 / RF-021)
        User::firstOrCreate(
            ['email' => 'admin@sena.edu.co'],
            [
                'name' => 'Administrador SENA',
                'password' => Hash::make('admin1234'),
                'rol_id' => $adminRole?->id,
                'estado' => User::ESTADO_ACTIVO,
            ]
        );

        // 2. Instructor (RF-021)
        User::firstOrCreate(
            ['email' => 'instructor@sena.edu.co'],
            [
                'name' => 'Instructor SENNOVA',
                'password' => Hash::make('instructor1234'),
                'rol_id' => $instructorRole?->id,
                'estado' => User::ESTADO_ACTIVO,
            ]
        );

        // 3. Aprendiz (RF-021)
        User::firstOrCreate(
            ['email' => 'aprendiz@sena.edu.co'],
            [
                'name' => 'Aprendiz SENA',
                'password' => Hash::make('aprendiz1234'),
                'rol_id' => $aprendizRole?->id,
                'estado' => User::ESTADO_ACTIVO,
            ]
        );

        // 4. Usuario Inactivo (para probar que el login bloquea según RF-020)
        User::firstOrCreate(
            ['email' => 'inactivo@sena.edu.co'],
            [
                'name' => 'Usuario Inhabilitado',
                'password' => Hash::make('inactivo1234'),
                'rol_id' => $aprendizRole?->id,
                'estado' => User::ESTADO_INACTIVO,
            ]
        );
    }
}

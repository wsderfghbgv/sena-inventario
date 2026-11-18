<?php

namespace Tests\Feature;

use App\Models\Auditoria;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermisoSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeguridadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermisoSeeder::class);
    }

    /**
     * RF-020: Inicio de sesión correcto para usuario activo.
     */
    public function test_usuario_activo_puede_iniciar_sesion(): void
    {
        $role = Role::where('nombre', Role::ADMIN)->first();
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@sena.edu.co',
            'password' => Hash::make('password123'),
            'rol_id' => $role->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@sena.edu.co',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('productos.index'));
        $this->assertAuthenticatedAs($user);

        // RF-023: Debe quedar registrado en auditoría
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'LOGIN',
            'modulo' => 'Autenticación',
        ]);
    }

    /**
     * RF-020: Bloqueo de inicio de sesión a usuarios inhabilitados.
     */
    public function test_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $role = Role::where('nombre', Role::APRENDIZ)->first();
        User::create([
            'name' => 'Usuario Inactivo',
            'email' => 'inactivo@sena.edu.co',
            'password' => Hash::make('password123'),
            'rol_id' => $role->id,
            'estado' => User::ESTADO_INACTIVO,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactivo@sena.edu.co',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // RF-023: Debe quedar registrado el intento de acceso denegado
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'ACCESO_DENEGADO',
            'modulo' => 'Autenticación',
        ]);
    }

    /**
     * RF-020: Cierre de sesión correcto.
     */
    public function test_usuario_puede_cerrar_sesion(): void
    {
        $role = Role::where('nombre', Role::ADMIN)->first();
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@sena.edu.co',
            'password' => Hash::make('password123'),
            'rol_id' => $role->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * RF-021: Administrador registra usuarios y asigna rol.
     */
    public function test_administrador_puede_crear_usuario_con_rol(): void
    {
        $adminRole = Role::where('nombre', Role::ADMIN)->first();
        $instructorRole = Role::where('nombre', Role::INSTRUCTOR)->first();

        $admin = User::create([
            'name' => 'Admin General',
            'email' => 'admin@sena.edu.co',
            'password' => Hash::make('password123'),
            'rol_id' => $adminRole->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('usuarios.store'), [
            'name' => 'Nuevo Instructor',
            'email' => 'nuevo.instructor@sena.edu.co',
            'password' => 'secret123',
            'rol_id' => $instructorRole->id,
            'estado' => 'activo',
        ]);

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'nuevo.instructor@sena.edu.co',
            'rol_id' => $instructorRole->id,
            'estado' => 'activo',
        ]);

        // RF-023: Registro de creación en auditoría
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'CREAR_USUARIO',
            'modulo' => 'Usuarios',
        ]);
    }

    /**
     * RF-022: Control de acceso en servidor - Usuarios no autorizados reciben 403.
     */
    public function test_aprendiz_no_puede_acceder_a_gestion_de_usuarios(): void
    {
        $aprendizRole = Role::where('nombre', Role::APRENDIZ)->first();
        $aprendiz = User::create([
            'name' => 'Aprendiz Test',
            'email' => 'aprendiz@sena.edu.co',
            'password' => Hash::make('password123'),
            'rol_id' => $aprendizRole->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);

        $this->actingAs($aprendiz);

        $response = $this->get(route('usuarios.index'));

        // Debe recibir Acceso Denegado (403)
        $response->assertStatus(403);
    }

    /**
     * RF-023: Inmutabilidad de los registros de auditoría.
     */
    public function test_registros_de_auditoria_son_inmutables(): void
    {
        $auditoria = Auditoria::create([
            'usuario_nombre' => 'Tester',
            'accion' => 'TEST_ACCION',
            'modulo' => 'Testing',
            'descripcion' => 'Prueba de inmutabilidad',
            'created_at' => now(),
        ]);

        $this->expectException(\Exception::class);
        $auditoria->update(['descripcion' => 'Intento de alteración']);
    }
}

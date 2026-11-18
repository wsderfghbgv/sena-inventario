<?php

namespace Tests\Feature;

use App\Models\productos;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermisoSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $aprendiz;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermisoSeeder::class);

        $adminRole = Role::where('nombre', Role::ADMIN)->first();
        $aprendizRole = Role::where('nombre', Role::APRENDIZ)->first();

        $this->admin = User::create([
            'name' => 'Admin SENA',
            'email' => 'admin@sena.edu.co',
            'password' => Hash::make('admin1234'),
            'rol_id' => $adminRole->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);

        $this->aprendiz = User::create([
            'name' => 'Aprendiz SENA',
            'email' => 'aprendiz@sena.edu.co',
            'password' => Hash::make('aprendiz1234'),
            'rol_id' => $aprendizRole->id,
            'estado' => User::ESTADO_ACTIVO,
        ]);
    }

    /**
     * RF-001: Registrar productos con código único y stock en 0.
     */
    public function test_administrador_puede_registrar_producto(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('productos.store'), [
            'codigo' => 'PROD-TEST-001',
            'nombre' => 'Multímetro Digital',
            'descripcion' => 'Instrumento de medición',
            'categoria' => 'Equipos de Medición',
            'unidad' => 'Unidad',
            'estado' => 'activo',
        ]);

        $response->assertRedirect(route('productos.index'));

        $this->assertDatabaseHas('productos', [
            'codigo' => 'PROD-TEST-001',
            'nombre' => 'Multímetro Digital',
            'stock' => 0,
            'estado' => 'activo',
        ]);

        // RF-023: Registro de creación en auditoría
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'CREAR_PRODUCTO',
            'modulo' => 'Productos',
        ]);
    }

    /**
     * RF-001: Validación de unicidad de código de producto.
     */
    public function test_no_permite_registrar_codigo_duplicado(): void
    {
        $this->actingAs($this->admin);

        productos::create([
            'codigo' => 'PROD-DUP-01',
            'nombre' => 'Osciloscopio',
            'categoria' => 'Equipos de Medición',
            'unidad' => 'Unidad',
            'stock' => 0,
            'estado' => 'activo',
        ]);

        $response = $this->post(route('productos.store'), [
            'codigo' => 'PROD-DUP-01',
            'nombre' => 'Otro Osciloscopio',
            'categoria' => 'Equipos de Medición',
            'unidad' => 'Unidad',
            'estado' => 'activo',
        ]);

        $response->assertSessionHasErrors(['codigo']);
    }

    /**
     * RF-002: Búsqueda y filtrado de productos.
     */
    public function test_filtrar_productos_por_termino_y_categoria(): void
    {
        $this->actingAs($this->admin);

        productos::create([
            'codigo' => 'FIL-01',
            'nombre' => 'Cautín Profesional',
            'categoria' => 'Herramientas',
            'unidad' => 'Unidad',
            'stock' => 5,
            'estado' => 'activo',
        ]);

        productos::create([
            'codigo' => 'FIL-02',
            'nombre' => 'Mouse USB',
            'categoria' => 'Tecnología y Cómputo',
            'unidad' => 'Unidad',
            'stock' => 10,
            'estado' => 'activo',
        ]);

        // Filtro por término de búsqueda
        $response = $this->get(route('productos.index', ['buscar' => 'Cautín']));
        $response->assertStatus(200);
        $response->assertSee('Cautín Profesional');
        $response->assertDontSee('Mouse USB');

        // Filtro por categoría
        $responseCat = $this->get(route('productos.index', ['categoria' => 'Tecnología y Cómputo']));
        $responseCat->assertStatus(200);
        $responseCat->assertSee('Mouse USB');
        $responseCat->assertDontSee('Cautín Profesional');
    }

    /**
     * RF-003: Modificar información de producto sin alterar código.
     */
    public function test_modificar_producto_conservando_codigo(): void
    {
        $this->actingAs($this->admin);

        $prod = productos::create([
            'codigo' => 'ORIG-100',
            'nombre' => 'Nombre Antiguo',
            'categoria' => 'Materiales e Insumos',
            'unidad' => 'Metro',
            'stock' => 15,
            'estado' => 'activo',
        ]);

        $response = $this->put(route('productos.update', $prod->id), [
            'nombre' => 'Nombre Actualizado',
            'categoria' => 'Materiales e Insumos',
            'unidad' => 'Metro',
            'estado' => 'inactivo',
        ]);

        $response->assertRedirect(route('productos.index'));

        $this->assertDatabaseHas('productos', [
            'id' => $prod->id,
            'codigo' => 'ORIG-100', // El código no cambia
            'nombre' => 'Nombre Actualizado',
            'estado' => 'inactivo',
            'stock' => 15, // El stock no se altera directamente en edición
        ]);

        // RF-023: Registro de actualización en auditoría
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'ACTUALIZAR_PRODUCTO',
            'modulo' => 'Productos',
        ]);
    }

    /**
     * RF-004: Eliminar producto disponible.
     */
    public function test_eliminar_producto_sin_historial(): void
    {
        $this->actingAs($this->admin);

        $prod = productos::create([
            'codigo' => 'DEL-01',
            'nombre' => 'Producto Desechable',
            'categoria' => 'Otros',
            'unidad' => 'Unidad',
            'stock' => 0,
            'estado' => 'activo',
        ]);

        $response = $this->delete(route('productos.destroy', $prod->id));

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseMissing('productos', ['id' => $prod->id]);

        // RF-023: Registro de eliminación en auditoría
        $this->assertDatabaseHas('auditorias', [
            'accion' => 'ELIMINAR_PRODUCTO',
            'modulo' => 'Productos',
        ]);
    }

    /**
     * RF-022: Aprendiz no puede crear productos (Acceso bloqueado 403).
     */
    public function test_aprendiz_recibe_acceso_denegado_al_crear_producto(): void
    {
        $this->actingAs($this->aprendiz);

        $response = $this->get(route('productos.create'));

        $response->assertStatus(403);
    }
}

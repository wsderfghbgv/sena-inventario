<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Valida que la ruta raíz redirija a login para visitantes (RF-020).
     */
    public function test_the_root_redirects_to_login_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Valida que la pantalla de inicio de sesión cargue exitosamente (RF-020).
     */
    public function test_the_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Iniciar Sesión');
    }
}

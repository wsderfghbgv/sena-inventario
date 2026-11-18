<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de Auditoría Inmutable de Acciones Críticas (RF-023)
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('usuario_nombre')->nullable(); // Respaldar nombre por si el usuario es eliminado
            $table->string('accion', 100);                // LOGIN, LOGOUT, CREAR, ACTUALIZAR, ELIMINAR, PERMISOS_MODIFICADOS
            $table->string('modulo', 50);                 // Autenticación, Productos, Ubicaciones, Usuarios, Permisos
            $table->text('descripcion');                  // Descripción legible de la acción crítica
            $table->json('datos_cambio')->nullable();     // Datos anteriores vs nuevos para auditoría forense
            $table->string('ip_address', 45)->nullable(); // Dirección IP del cliente
            $table->text('user_agent')->nullable();       // Navegador / cliente
            $table->timestamp('created_at')->useCurrent(); // Fecha y hora exacta (sin updated_at: inmutable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};

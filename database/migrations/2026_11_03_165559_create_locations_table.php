<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla 'locations'.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            // Clave primaria autoincremental de la tabla
            $table->id();
            
            // RF-013: Código identificador único (Ej: B1-Z2-E3). 'unique()' impide que se repita.
            $table->string('code')->unique(); 
            
            // Nombre descriptivo de la ubicación física (Ej: "Estante Principal A")
            $table->string('name');
            
            // Define el nivel jerárquico. 'enum' restringe los datos solo a estas 5 opciones.
            $table->enum('type', ['bodega', 'zona', 'estante', 'nivel', 'espacio']);
            
            // Patrón "Adjacency List" para jerarquía infinita en una sola tabla:
            // - foreignId: Crea la columna 'parent_id'.
            // - nullable: Permite que esté vacía (vital para la Bodega raíz que no tiene padre).
            // - constrained('locations'): Crea la llave foránea apuntando a esta misma tabla.
            // - nullOnDelete: Si se borra una zona, sus estantes no se borran, su parent_id queda en NULL.
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            
            // RF-016: Control de estado. Por defecto toda nueva ubicación nace activa (true).
            $table->boolean('is_active')->default(true);
            
            // RF-018: Coordenadas espaciales para la maquetación del plano interactivo 2D.
            // Son 'nullable' porque una bodega general podría no necesitar coordenadas específicas.
            $table->integer('grid_row')->nullable();$table->integer('grid_column')->nullable();
            
            // Crea automáticamente las columnas 'created_at' y 'updated_at' para auditoría.
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (Elimina la tabla si hacemos un rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
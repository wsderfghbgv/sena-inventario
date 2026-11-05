<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // RF-001: Código único
            $table->string('nombre');          // RF-001: Nombre
            $table->text('descripcion')->nullable(); // RF-001: Descripción
            $table->string('categoria');       // RF-001: Categoría
            $table->string('unidad');          // RF-001: Unidad de medida
            $table->integer('stock')->default(0); // Existencias iniciales en 0
            $table->string('estado')->default('activo'); // RF-004: activo o inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique();
            $table->date('fecha');

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('ubicacion_id')
                ->constrained('locations')
                ->restrictOnDelete();

            $table->text('observaciones')->nullable();

            $table->string('estado')->default('borrador');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyecto_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('tipo_id')->constrained('tipos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['proyecto_id', 'user_id', 'tipo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_accesos');
    }
};

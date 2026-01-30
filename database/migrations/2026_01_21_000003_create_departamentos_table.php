<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                  ->constrained('proyectos')
                  ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();

            // Un mismo proyecto no puede tener dos departamentos con el mismo nombre
            $table->unique(['project_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};

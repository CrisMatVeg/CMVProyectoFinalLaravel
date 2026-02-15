<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuario_departamento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            $table->foreignId('department_id')
                  ->constrained('departamentos')
                  ->cascadeOnDelete();

            $table->foreignId('project_id')
                  ->constrained('proyectos')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Un usuario solo puede pertenecer una vez a un departamento
            $table->unique(['user_id', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_departamento');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tarea_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        
            $table->unique(['task_id', 'user_id']); // evita duplicados
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_departamento');
    }
};

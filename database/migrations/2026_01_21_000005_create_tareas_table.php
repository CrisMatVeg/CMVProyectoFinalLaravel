<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('tipos')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('estados')->onDelete('cascade');
            $table->decimal('estimated_hours', 8, 2)->default(0);
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};

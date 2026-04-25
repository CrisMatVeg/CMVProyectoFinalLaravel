<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->boolean('is_milestone')->default(false)->after('end_date');
        });

        Schema::create('tarea_dependencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('depends_on_id')->constrained('tareas')->onDelete('cascade');
            $table->unique(['task_id', 'depends_on_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarea_dependencias');
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('is_milestone');
        });
    }
};

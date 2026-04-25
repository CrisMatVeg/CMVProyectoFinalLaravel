<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->decimal('actual_hours', 8, 2)->default(0);
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('participaciones');
    }
};

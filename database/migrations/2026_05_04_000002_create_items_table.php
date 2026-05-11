<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('game_id')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('precio');
            $table->enum('tipo', ['Arma', 'Consumible', 'Misión']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

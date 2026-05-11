<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dialogos', function (Blueprint $table) {
            $table->id();
            $table->string('id_conversacion');
            $table->integer('orden');
            $table->string('personaje_id');
            $table->text('texto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dialogos');
    }
};

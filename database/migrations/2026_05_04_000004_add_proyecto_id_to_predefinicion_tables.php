<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personajes', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->after('id')->constrained('proyectos')->cascadeOnDelete();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->after('id')->constrained('proyectos')->cascadeOnDelete();
        });

        Schema::table('dialogos', function (Blueprint $table) {
            $table->foreignId('proyecto_id')->after('id')->constrained('proyectos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('personajes', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
            $table->dropColumn('proyecto_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
            $table->dropColumn('proyecto_id');
        });

        Schema::table('dialogos', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
            $table->dropColumn('proyecto_id');
        });
    }
};

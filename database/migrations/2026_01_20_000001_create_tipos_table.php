<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();
        DB::table('tipos')->insertOrIgnore([
            ['name' => 'Desarrollo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Diseño',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Audio',      'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Narrativa',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Marketing',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arte',       'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};

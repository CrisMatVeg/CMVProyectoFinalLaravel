<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->dropColumn('max_uses');
        });
    }

    public function down(): void
    {
        Schema::table('invitaciones', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_uses')->nullable();
        });
    }
};

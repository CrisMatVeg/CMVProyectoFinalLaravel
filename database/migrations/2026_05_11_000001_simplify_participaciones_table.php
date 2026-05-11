<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('participaciones', function (Blueprint $table) {
            $table->dropColumn(['proposed_at', 'accepted_at', 'actual_hours']);
        });
    }

    public function down(): void
    {
        Schema::table('participaciones', function (Blueprint $table) {
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->decimal('actual_hours', 8, 2)->default(0);
        });
    }
};

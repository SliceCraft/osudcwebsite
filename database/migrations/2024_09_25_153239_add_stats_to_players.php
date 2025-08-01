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
        Schema::table('players', function (Blueprint $table) {
            $table->float('average_accuracy')->nullable(false)->default(0);
            $table->integer('total_attempts')->nullable(false)->default(0);
            $table->integer('current_streak')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('average_accuracy');
            $table->dropColumn('total_attempts');
            $table->dropColumn('current_streak');
        });
    }
};

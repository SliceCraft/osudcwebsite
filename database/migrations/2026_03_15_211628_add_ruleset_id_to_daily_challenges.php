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
        Schema::table('daily_challenges', function (Blueprint $table) {
            $table->integer('ruleset_id')->nullable();
            $table->integer('beatmap_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_challenges', function (Blueprint $table) {
            $table->dropColumn('ruleset_id');
            $table->dropColumn('beatmap_id');
        });
    }
};

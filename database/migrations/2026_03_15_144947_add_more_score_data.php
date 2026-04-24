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
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('mehs')->nullable();
            $table->integer('goods')->nullable();
            $table->integer('combo')->nullable();
            $table->integer('large_tick_misses')->nullable();
            $table->integer('slider_tail_misses')->nullable();
            $table->json('mods')->nullable();
            $table->integer('misses')->nullable();
            $table->float('pp')->nullable();
            $table->bigInteger('score_id')->unique();
            $table->integer('pp_version')->nullable();
            $table->integer('attempts')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('mehs');
            $table->dropColumn('goods');
            $table->dropColumn('combo');
            $table->dropColumn('large_tick_misses');
            $table->dropColumn('slider_tail_misses');
            $table->dropColumn('mods');
            $table->dropColumn('misses');
            $table->dropColumn('pp');
            $table->dropColumn('score_id');
            $table->dropColumn('pp_version');
            $table->integer('attempts')->nullable(false)->change();
        });
    }
};

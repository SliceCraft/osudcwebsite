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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->nullable(false);
            $table->integer('daily_challenge')->nullable(false);
            $table->integer('score')->nullable(false);
            $table->float('accuracy')->nullable(false);
            $table->integer('attempts')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};

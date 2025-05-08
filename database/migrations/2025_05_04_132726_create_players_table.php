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
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('player_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('last_name');
            $table->integer('age');
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('position');
            $table->integer('jersey_number');
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

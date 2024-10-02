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
        Schema::table('users', function (Blueprint $table) {
            $table->integer(column: 'total_points')->default(0);
        });

        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('game_state');
            $table->foreignUuid('player_turn')->constrained('users');
            $table->foreignUuid('host_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('game_user', function (Blueprint $table) {
            $table->foreignUuid('game_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->integer('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_points');
            $table->dropColumn('game_points');
        });
    }
};

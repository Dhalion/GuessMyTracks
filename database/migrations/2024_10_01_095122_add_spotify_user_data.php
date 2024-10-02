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
            $table->string('username')->after('name')->nullable();
            $table->string('image_url')->after('email')->nullable();
            $table->integer('total_tracks')->after('image_url')->nullable();
            $table->string('spotify_access_token', 2048)->after('total_tracks')->nullable();
            $table->string('spotify_refresh_token', 2048)->after('spotify_access_token')->nullable();
            // make password nullable
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('image_url');
            $table->dropColumn('total_tracks');
            $table->dropColumn('spotify_access_token');
            $table->dropColumn('spotify_refresh_token');
            // make password not nullable
            $table->string('password')->change();
        });
    }
};

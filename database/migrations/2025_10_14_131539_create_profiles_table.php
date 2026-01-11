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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
        });

        Schema::create('user_profile', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('profile_id');
            $table->primary(['user_id', 'profile_id']);
            $table->timestamps();// timestamps for user-profile assignments
        });

        Schema::create('profile_permission', function (Blueprint $table) {
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['profile_id', 'permission_id']);
        });

        Schema::create('user_permission', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['user_id', 'permission_id']);
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

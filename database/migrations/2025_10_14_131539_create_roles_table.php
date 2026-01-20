<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * RBAC Authorization Schema:
     * - roles: Define roles (admin, staff, etc.)
     * - role_user: Assign roles to users (many-to-many)
     * - permission_role: Assign permissions to roles (many-to-many)
     * 
     * Users receive permissions ONLY through roles.
     */
    public function up(): void
    {
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED
            $table->string('title', 50);
            $table->string('slug', 50)->unique()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Pivot: Users <-> Roles (many-to-many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
            $table->timestamps();
        });

        // Pivot: Roles <-> Permissions (many-to-many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        // Now add the foreign key to users.active_role_id since roles table exists
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('active_role_id')->references('id')->on('roles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key from users table first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_role_id']);
        });

        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};

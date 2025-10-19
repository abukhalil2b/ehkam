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

        Schema::create('organizational_units', function (Blueprint $table) {
            $table->id();

            // Name of the unit (e.g., "General Directorate of Finance")
            $table->string('name');

            // Type of unit for clear categorization (e.g., 'Directorate', 'Department', 'Section')
            $table->string('type')->index();

            // --- SELF-REFERENCING FOREIGN KEY ---
            // Links this unit to its parent unit. Nullable for top-level Directorates.
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organizational_units') // References itself
                ->onDelete('cascade'); // Deleting a parent deletes all children

            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();

            $table->foreignId('reports_to_position_id')
                ->nullable()
                ->constrained('positions') // Constraining itself
                ->onDelete('set null'); // If a senior position is deleted, junior positions remain, reporting to null

            $table->tinyInteger('ordered')->default(0);
            $table->timestamps();
        });


        // This pivot table determines which positions can exist within which units.
        Schema::create('organizational_unit_position', function (Blueprint $table) {

            $table->foreignId('organizational_unit_id')
                ->constrained('organizational_units')
                ->onDelete('cascade');

            $table->foreignId('position_id')
                ->constrained('positions')
                ->onDelete('cascade');

            // Composite primary key to ensure uniqueness
            $table->primary(['organizational_unit_id', 'position_id'], 'unit_position_primary');
        });

        // --- SEEDING NOTE ---
        // After running this migration, you would need to insert data into this table
        // to define the valid combinations (e.g., 'Director Position' is available in 'Directorate Unit').

        // B. User Position History Table (The corrected pivot/history table)
        Schema::create('user_position_history', function (Blueprint $table) {
            // Foreign Keys
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // --- ESSENTIAL HISTORY FIELDS ---
            // Tracks when the user STARTED the position.
            $table->date('start_date');

            // Tracks when the user ENDED the position. NULL if it is their current role.
            $table->date('end_date')->nullable();

            // Optional: Reference the Organizational Unit (Directorate/Department) where they held the position.
            // Assumes you have a separate 'organizational_units' table for hierarchy (as discussed previously).
            $table->foreignId('organizational_unit_id')
                ->nullable()
                ->constrained('organizational_units')
                ->onDelete('set null');


            // Primary key must include start_date to allow a user to hold the same position multiple times.
            $table->primary(['position_id', 'user_id', 'start_date'], 'user_position_primary');

            $table->timestamps(); // Record when this history entry was created/updated in the database.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_unit_position');

        // 1. Drop the table that depends on others (user_position_history)
        Schema::dropIfExists('user_position_history');

        // 2. Drop the positions table
        Schema::dropIfExists('positions');

        // 3. Drop the organizational_units table
        Schema::dropIfExists('organizational_units');
    }
};

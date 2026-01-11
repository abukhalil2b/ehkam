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

        Schema::create('org_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->unique();
            $table->string('name');
            $table->string('type')->index(); // Minister, Directorate, Department, Section, Expert
            $table->foreignId('parent_id')->nullable()
                ->constrained('org_units')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('job_code')->unique();
            $table->string('title');
            $table->foreignId('reports_to_position_id')->nullable()
                ->constrained('positions')->nullOnDelete();
            $table->tinyInteger('ordered')->default(0);
            $table->timestamps();
        });

        Schema::create('org_unit_positions', function (Blueprint $table) {
            $table->foreignId('org_unit_id')->constrained('org_units')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('positions')->cascadeOnDelete();
            $table->primary(['org_unit_id', 'position_id']);
        });

        Schema::create('employee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('positions');
            $table->foreignId('org_unit_id')->nullable()
                ->constrained('org_units')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_unit_position');

        // 1. Drop the table that depends on others (employee_assignment)
        Schema::dropIfExists('employee_assignment');

        // 2. Drop the positions table
        Schema::dropIfExists('positions');

        // 3. Drop the org_units table
        Schema::dropIfExists('org_units');
    }
};

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
            $table->string('type')->index(); // Minister, Undersecretary, Directorate, Department, Section, Expert
            $table->unsignedTinyInteger('hierarchy_order')->default(0); // للترتيب المخصص
            $table->foreignId('parent_id')->nullable()
                ->constrained('org_units')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('job_code')->unique();
            $table->string('title');
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
            $table->enum('assignment_type', ['PERMANENT', 'SECONDMENT', 'ACTING', 'TEMPORARY', 'DELEGATION'])
                ->default('PERMANENT');
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // لتسريع جلب "الحالة الحالية"
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
        // منع تداخل تعيينين دائمين
        // لا يسمح بوجود أكثر من:
        // PERMANENT
        // بنفس الفترة الزمنية
        // 3.2 السماح بتعدد:
        // SECONDMENT
        // ACTING
        // 3.3 إنهاء التعيين
        // عند انتهاء end_date:
        // is_active = false (cron أو observer)
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

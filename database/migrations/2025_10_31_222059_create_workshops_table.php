<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('starts_at'); // For better query performance
            $table->index('is_active');
        });

        Schema::create('workshop_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')
                ->constrained('workshops')
                ->onDelete('cascade');
            $table->string('attendee_name'); // More explicit than 'name'
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
            $table->index('workshop_id');
        });

        Schema::create('workshop_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')->constrained('workshops')->onDelete('cascade');
            $table->date('day_date');
            $table->string('label')->nullable(); // e.g., "Day 1", "Session AM"
            $table->boolean('is_active')->default(false); // To denote if this is the currently active day for check-in
            $table->timestamps();
        });

        Schema::create('workshop_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_day_id')->constrained('workshop_days')->onDelete('cascade');
            $table->foreignId('workshop_attendance_id')->constrained('workshop_attendances')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->timestamp('checkin_time')->useCurrent();
            $table->timestamps();

            $table->unique(['workshop_day_id', 'workshop_attendance_id'], 'unique_checkin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_checkins');
        Schema::dropIfExists('workshop_days');
        Schema::dropIfExists('workshop_attendances');
        Schema::dropIfExists('workshops');
    }
};

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
        // ==========================================================
        // 1. Calendar Events Table
        // ==========================================================
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();

            // --- Ownership & Context ---
            // The 'user_id' is the person who physically clicked "Create".
            // If they are deleted, their events are deleted (cascade).
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // HINT: We make this nullable because an event might belong to an OrgUnit, not a specific person.
            // CRITICAL FIX: Added 'constrained' to ensure data integrity.
            $table->foreignId('target_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            // New field for Organizational Events.
            // HINT: nullOnDelete() is safer here. If an OrgUnit is deleted, we might want to keep the event 
            // but just detach it from the unit (or you could cascade if you prefer strict cleanup).
            $table->foreignId('org_unit_id')
                ->nullable()
                ->constrained('org_units')
                ->nullOnDelete();

            // --- Event Details ---
            $table->string('title');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('hijri_date', 100)->nullable(); // Optional, calculated via helper

            // --- Classification & UI ---
            $table->string('type', 50);      // Enum-like: meeting, conference, etc.
            $table->string('bg_color', 7);   // Length 7 is exact for Hex codes (e.g., #FFFFFF)
            $table->string('program', 100)->nullable();
            $table->text('notes')->nullable();

            // --- Filtering & Logic ---
            // HINT: We store 'year' separately and index it because 99% of your queries 
            // will be "Show me events for 2026". Calculating YEAR(start_date) on the fly is slow.
            $table->year('year')->index();
            $table->boolean('is_public')->default(true);

            $table->timestamps();

            // --- Performance Indexes ---
            // Composite indexes speed up specific queries like "Show User X's events in 2026"
            $table->index(['target_user_id', 'year']);
            $table->index(['org_unit_id', 'year']); // CRITICAL FIX: Added for department view performance
            $table->index(['start_date', 'end_date']); // For conflict checking queries
        });

        // ==========================================================
        // 2. Permissions Table (Who can post to an OrgUnit?)
        // ==========================================================
        Schema::create('calendar_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('org_unit_id')->constrained('org_units')->cascadeOnDelete();

            // HINT: Default role 'editor' allows posting. 'admin' might allow deleting others' posts.
            $table->string('role')->default('editor');
            $table->timestamps();

            // CRITICAL: Prevents a user from having multiple permission rows for the same Unit.
            $table->unique(['user_id', 'org_unit_id']);
        });

        // ==========================================================
        // 3. Delegations Table (Who can post on another User's calendar?)
        // ==========================================================
        Schema::create('calendar_delegations', function (Blueprint $table) {
            $table->id();

            // Both columns reference the 'users' table
            $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();

            $table->boolean('is_active')->default(true);

            // HINT: Tracking when permission was given/revoked is useful for HR audits.
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            // CRITICAL: A manager cannot delegate to the same employee twice.
            $table->unique(['manager_id', 'employee_id']);
            $table->index(['manager_id', 'is_active']); // Fast lookup for "Who do I manage?"
        });

        // ==========================================================
        // 4. Audit Logs (Who did what?)
        // ==========================================================
        Schema::create('calendar_audit_logs', function (Blueprint $table) {
            $table->id();

            // CRITICAL FIX: nullOnDelete().
            // If an Event is deleted, we want the Log to REMAIN so we know who deleted it.
            // If we used cascadeOnDelete, deleting the event would wipe the evidence.
            $table->foreignId('calendar_event_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The actor

            $table->enum('action', ['created', 'updated', 'deleted', 'moved']);

            // HINT: JSON columns allow us to store exact snapshots of the event before/after changes.
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['calendar_event_id', 'created_at']); // History of an event
            $table->index(['user_id', 'created_at']); // Activity log of a user
        });

        // ==========================================================
        // 5. Slot Proposals (Appointment System)
        // ==========================================================
        Schema::create('calendar_slot_proposals', function (Blueprint $table) {
            $table->id();

            // WARNING: Ensure 'appointment_requests' migration runs BEFORE this one.
            $table->foreignId('appointment_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('location')->nullable();

            $table->enum('status', ['proposed', 'accepted', 'rejected'])
                ->default('proposed');

            $table->foreignId('created_by')->constrained('users');

            // HINT: Tracks who made the final decision (Minister or Secretary).
            $table->foreignId('selected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_slot_proposals');
        Schema::dropIfExists('calendar_audit_logs');
        Schema::dropIfExists('calendar_delegations');
        Schema::dropIfExists('calendar_events');
    }
};

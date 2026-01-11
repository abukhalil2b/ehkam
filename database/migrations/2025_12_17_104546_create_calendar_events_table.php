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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();

            // Ownership & Permissions
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The creator/owner
            $table->foreignId('target_user_id')->nullable()->constrained('users'); // If I write on someone else's page

            $table->string('title');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('hijri_date', 100)->nullable();

            // classification
            $table->string('type', 50);      // meeting, conference, competition...
            $table->string('bg_color', 7);      // #c1c1c1
            $table->string('program', 100)->nullable(); // الإجازة القرآنية، المؤتمر...
            // optional metadata
            $table->text('notes')->nullable();
            // for filtering/reporting
            $table->year('year')->index();
            $table->boolean('is_public')->default(true); // Allow private events on personal pages
            $table->timestamps();
            $table->index(['target_user_id', 'year']);
            $table->index(['start_date', 'end_date']);
        });

        Schema::create('calendar_delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('granted_at')->useCurrent();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->unique(['manager_id', 'employee_id']);
            $table->index(['manager_id', 'is_active']);
        });

        Schema::create('calendar_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('action', ['created', 'updated', 'deleted', 'moved']);
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['calendar_event_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};

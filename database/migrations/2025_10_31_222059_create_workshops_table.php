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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_attendances');
        Schema::dropIfExists('workshops');
    }
};

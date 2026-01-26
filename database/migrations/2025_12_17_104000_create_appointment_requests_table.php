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
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('minister_id')->constrained('users');

            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('priority')->default('normal');

            $table->foreignId('current_stage_id')
                ->nullable()
                ->constrained('workflow_stages')
                ->nullOnDelete();

            $table->enum('status', ['draft', 'in_progress', 'rejected', 'booked'])
                ->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};

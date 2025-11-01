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
            $table->string('place')->nullable();// conference rooms
            $table->boolean('active')->default(false);
           $table->foreignId('written_by')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('workshop_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')
                ->constrained('workshops')
                ->onDelete('cascade'); // cascade delete if parent deleted
            $table->string('name'); // only attendee name
            $table->string('job_title')->nullable(); // only attendee name
            $table->string('department')->nullable(); // only attendee name
            $table->timestamps();
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

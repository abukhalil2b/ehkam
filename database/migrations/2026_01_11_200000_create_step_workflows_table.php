<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('step_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained()->cascadeOnDelete();

            // Workflow Stage
            $table->string('stage')->default('target_setting');
            // Enum: target_setting, execution, verification, approval, completed

            // Status within the stage
            $table->string('status')->default('pending');
            // Enum: pending, approved, rejected

            // Assignments
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_role')->nullable()->constrained('profiles')->nullOnDelete();

            $table->text('comments')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('step_workflows');
    }
};

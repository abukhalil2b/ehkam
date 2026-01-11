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
        Schema::create('step_org_unit_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')
                ->constrained('steps')
                ->onDelete('cascade');

            $table->unsignedBigInteger('org_unit_id');

            $table->foreignId('period_template_id')
                ->constrained('period_templates')
                ->onDelete('cascade');

            $table->integer('target')->default(0);
            $table->integer('achieved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('step_org_unit_tasks');
    }
};

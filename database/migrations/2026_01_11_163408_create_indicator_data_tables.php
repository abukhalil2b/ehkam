<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('indicator_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('period_index'); // 1, 2, 3, 4 (for quarterly) or 1..12 (for monthly)
            $table->decimal('target_value', 16, 2);
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['indicator_id', 'sector_id', 'year', 'period_index'], 'ind_target_unique');
        });

        Schema::create('indicator_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('period_index');
            $table->decimal('achieved_value', 16, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint
            $table->unique(['indicator_id', 'sector_id', 'year', 'period_index'], 'ind_achieved_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('indicator_achievements');
        Schema::dropIfExists('indicator_targets');
    }
};

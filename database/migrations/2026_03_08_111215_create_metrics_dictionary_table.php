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
        Schema::create('metrics_dictionary', function (Blueprint $table) {
            $table->id();
            $table->enum('sector_name', ['quran', 'zakah', 'guidance', 'endowment', 'mosque', 'fatwa']);
            $table->string('metric_key')->unique();
            $table->string('name_ar');
            $table->enum('data_type', ['integer', 'decimal', 'percentage']);
            $table->enum('aggregation_type', ['sum', 'count', 'average']);
            $table->string('source_table');
            $table->string('source_column');
            $table->boolean('is_derived')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics_dictionary');
    }
};

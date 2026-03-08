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
        Schema::create('quran_school_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorate_id')->constrained();
            $table->foreignId('wilayat_id')->nullable()->constrained();
            $table->year('year');

            // Traditional Education Metrics
            $table->integer('traditional_schools_count')->default(0);
            $table->integer('traditional_classes_count')->default(0);
            $table->integer('traditional_teachers_count')->default(0);
            $table->integer('supervisors_count')->default(0);

            // Distance Education Metrics
            $table->integer('distance_classes_count')->default(0);
            $table->integer('distance_teachers_count')->default(0);
            $table->integer('distance_semesters_count')->default(0);

            $table->timestamps();

            $table->unique(['governorate_id', 'wilayat_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_school_statistics');
    }
};

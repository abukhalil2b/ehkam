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
        Schema::create('quran_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayat_id')->constrained();
            $table->enum('education_mode', ['traditional', 'distance']);
            $table->enum('gender', ['male', 'female']);
            $table->integer('age');
            $table->enum('period', ['morning', 'evening']);
            $table->foreignId('program_id')->nullable()->constrained('quran_programs')->nullOnDelete();
            $table->enum('semester', ['first', 'winter', 'second', 'summer']);
            $table->enum('status', ['enrolled', 'graduated', 'dropped']);
            $table->year('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_students');
    }
};

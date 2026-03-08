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
        Schema::create('endowments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المؤسسة الوقفية
            $table->enum('type', ['عامة', 'خاصة']); // النوع
            $table->foreignId('governorate_id')->constrained();
            $table->timestamps();
        });

        Schema::create('endowment_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endowment_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->integer('employees_count')->default(0);
            $table->decimal('revenues', 15, 3)->default(0); // الإيرادات (تم استخدام decimal للعملات)
            $table->decimal('expenses', 15, 3)->default(0); // المصروفات
            $table->timestamps();

            $table->unique(['endowment_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endowments');
    }
};

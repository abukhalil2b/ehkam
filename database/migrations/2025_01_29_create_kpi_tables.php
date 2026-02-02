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
        // جدول المؤشرات الرئيسية
        Schema::create('kpi_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // KPI-001, KPI-002, etc.
            $table->string('title'); // اسم المؤشر
            $table->string('unit')->default('number'); // وحدة القياس: number, currency, percentage
            $table->string('currency')->nullable(); // OMR, USD, etc.
            $table->text('description')->nullable(); // وصف المؤشر
            $table->string('category')->nullable(); // تصنيف المؤشر
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->timestamps();
        });

        // جدول القيم المستهدفة والمحققة
        Schema::create('kpi_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_indicator_id')->constrained('kpi_indicators')->cascadeOnDelete();
            $table->unsignedSmallInteger('year'); // السنة: 2023, 2024, 2025
            $table->unsignedTinyInteger('quarter'); // الربع: 1, 2, 3, 4
            $table->decimal('target_value', 15, 2)->default(0); // القيمة المستهدفة
            $table->decimal('actual_value', 15, 2)->default(0); // القيمة المحققة
            $table->text('justification')->nullable(); // المبررات
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();

            // فهرس مركب لتسريع البحث
            $table->unique(['kpi_indicator_id', 'year', 'quarter'], 'kpi_values_unique');
            $table->index(['year', 'quarter']);
        });

        // جدول المبررات والتحليلات السنوية (اختياري)
        Schema::create('kpi_annual_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_indicator_id')->constrained('kpi_indicators')->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->text('analysis')->nullable(); // التحليل السنوي
            $table->text('challenges')->nullable(); // التحديات
            $table->text('recommendations')->nullable(); // التوصيات
            $table->timestamps();

            $table->unique(['kpi_indicator_id', 'year']);
        });

        Schema::create('kpi_report_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('setting_key', 100);
            $table->text('setting_value')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'setting_key'], 'user_setting_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_annual_reports');
        Schema::dropIfExists('kpi_values');
        Schema::dropIfExists('kpi_indicators');
    }
};

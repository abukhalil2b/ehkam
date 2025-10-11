<?php

use App\Models\Indicator;
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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('main_criteria')->nullable(); // المعيار الرئيسي
            $table->string('sub_criteria')->nullable(); // المعيار الفرعي
            $table->string('code')->nullable(); // رمز المؤشر
            $table->string('owner')->nullable(); // مالك المؤشر
            $table->text('description')->nullable(); // وصف المؤشر
            $table->text('measurement_tool')->nullable(); // أداة القياس
            $table->string('polarity')->nullable(); // قطبية القياس
            $table->text('polarity_description')->nullable(); // شرح قطبية القياس
            $table->string('unit')->nullable(); // وحدة القياس
            $table->string('formula')->nullable(); // معادلة القياس
            $table->string('first_observation_date')->nullable(); // تاريخ الرصد الأول
            $table->text('baseline_formula')->nullable(); // معادلة احتساب خط الأساس
            $table->string('baseline_after_application')->nullable(); // خط الأساس بعد التطبيق
            $table->text('survey_question')->nullable(); // اسئلة الاستبيان (سؤال للتحقق)
            $table->text('proposed_initiatives')->nullable(); // مبادرات ومشاريع مقترحة
            $table->string('period',11);//period_templates
            $table->foreignIdFor(Indicator::class,'parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};

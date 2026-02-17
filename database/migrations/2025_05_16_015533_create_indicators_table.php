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
            $table->boolean('is_main')->default(1);
            $table->string('main_criteria')->nullable(); // المعيار الرئيسي
            $table->string('sub_criteria')->nullable(); // المعيار الفرعي
            $table->string('code')->nullable(); // رمز المؤشر
            $table->string('owner')->nullable(); // مالك المؤشر
            $table->text('description')->nullable(); // وصف المؤشر
            $table->text('measurement_tool')->nullable(); // أداة القياس
            $table->string('polarity')->nullable(); // قطبية القياس
            $table->text('polarity_description')->nullable(); // شرح قطبية القياس
            $table->string('unit')->default('percentage'); // وحدة القياس
            $table->string('formula')->nullable(); // معادلة القياس
            $table->date('first_observation_date')->nullable(); // تاريخ الرصد الأول
            $table->text('baseline_formula')->nullable(); // معادلة احتساب خط الأساس
            $table->text('survey_question')->nullable(); // اسئلة الاستبيان (سؤال للتحقق)
            $table->text('proposed_initiatives')->nullable(); // مبادرات ومشاريع مقترحة
            $table->string('evidence_type')->nullable();
            $table->text('sectors')->nullable(); //array get it from sectors tables
            $table->foreignIdFor(Indicator::class, 'parent_id')->nullable();
            $table->string('period')->default('quarterly'); //annually - half_yearly - quarterly - monthly
            $table->string('baseline_value', 50)->nullable(); // Stores "3 Million (2022)"
            $table->decimal('baseline_numeric', 14, 2)->default(80); // Stores 3000000.00 for calculations
            $table->smallInteger('baseline_year')->default(2022);
            $table->timestamps();
        });

        Schema::create('indicator_targets', function (Blueprint $table) {
            $table->id();
            $table->enum('target_for', ['indicator', 'sector'])->default('indicator');
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('period_index');
            /* * EXPLANATION:
            * This column identifies WHICH period within the year this target belongs to.
            * It depends on the 'period' column in the 'indicators' table:
            * * - If period = 'annually':  period_index is always 1.
            * - If period = 'semi_annually': 1 (First Half), 2 (Second Half).
            * - If period = 'quarterly': 1 (Q1), 2 (Q2), 3 (Q3), 4 (Q4).
            * - If period = 'monthly':   1 (Jan) ... to ... 12 (Dec).
            */
            // The target value. 
            // If unit is '%', store 5.00 for 5%. 
            // If unit is 'Number', store 5400.00.
            $table->decimal('target_value', 20, 2);
            $table->enum('unit', ['percentage', 'number'])->default('number'); // To clarify the unit of the target value
            // Optional: To verify if this was a manual entry from PDF or auto-calculated
            $table->boolean('is_calculated')->default(false);

            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['indicator_id', 'sector_id', 'year', 'period_index'], 'ind_target_unique');
        });

        Schema::create('indicator_achievements', function (Blueprint $table) {
            $table->id();
            $table->enum('achieved_by', ['indicator', 'sector'])->default('indicator');
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('period_index')->nullable();
            $table->decimal('achieved_value', 16, 2);
            $table->enum('unit', ['percentage', 'number'])->default('number'); // To clarify the unit of the target value
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint
            $table->unique(['indicator_id', 'sector_id', 'year', 'period_index'], 'ind_achieved_unique');
        });

        Schema::create('indicator_sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->decimal('baseline_numeric', 14, 2)->default(0); // خط أساس القطاع
            $table->smallInteger('baseline_year')->default(2022);// سنة أساس القطاع
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

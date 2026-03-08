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
        Schema::create('guidance_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorate_id')->constrained();
            $table->foreignId('wilayat_id')->nullable()->constrained(); // يقبل null إذا كانت الإحصائية للمحافظة ككل
            $table->year('year');

            // الأئمة والخطباء والمؤذنون
            $table->integer('imams_and_preachers_count')->default(0);
            $table->integer('muezzins_count')->default(0);

            // الموجهون
            $table->integer('mentors_male')->default(0);
            $table->integer('mentors_female')->default(0);

            // الوعاظ
            $table->integer('preachers_male')->default(0);
            $table->integer('preachers_female')->default(0);

            // المرشدون الدينيون
            $table->integer('religious_guides_male')->default(0);
            $table->integer('religious_guides_female')->default(0);

            // المشرفون الدينيون
            $table->integer('supervisors_male')->default(0);
            $table->integer('supervisors_female')->default(0);

            $table->timestamps();

            // لضمان عدم تكرار الإحصائية لنفس المكان في نفس السنة
            $table->unique(['governorate_id', 'wilayat_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guidance_statistics');
    }
};

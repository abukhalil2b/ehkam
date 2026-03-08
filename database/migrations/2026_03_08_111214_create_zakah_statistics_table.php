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
        Schema::create('zakah_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorate_id')->nullable()->constrained();
            $table->foreignId('wilayat_id')->nullable()->constrained();
            $table->year('year');

            $table->decimal('annual_target', 15, 2)->default(0);
            $table->decimal('q1_target', 15, 2)->default(0);
            $table->decimal('q2_target', 15, 2)->default(0);
            $table->decimal('q3_target', 15, 2)->default(0);
            $table->decimal('q4_target', 15, 2)->default(0);

            $table->decimal('q1_achieved', 15, 2)->default(0);
            $table->decimal('q2_achieved', 15, 2)->default(0);
            $table->decimal('q3_achieved', 15, 2)->default(0);
            $table->decimal('q4_achieved', 15, 2)->default(0);
            $table->decimal('total_collected_distributed', 15, 2)->virtualAs('q1_achieved + q2_achieved + q3_achieved + q4_achieved');

            $table->integer('beneficiary_families_count')->default(0);
            $table->integer('local_committees_count')->default(0);
            $table->decimal('electronic_transfers_amount', 15, 2)->default(0);

            $table->timestamps();

            $table->unique(['governorate_id', 'wilayat_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakah_statistics');
    }
};

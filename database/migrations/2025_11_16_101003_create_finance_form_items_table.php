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
        Schema::create('finance_form_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('finance_form_id')->constrained('finance_forms')->onDelete('cascade');
            $table->foreignId('finance_need_id')->constrained('finance_needs')->onDelete('cascade');

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_form_items');
    }
};

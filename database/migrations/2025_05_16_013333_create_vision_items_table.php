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
        Schema::create('vision_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            // حقل parent_id يقبل القيمة null (لأن المحاور الرئيسية ليس لها أب)
            // ويرتبط بنفس الجدول
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('vision_items')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vision_items');
    }
};

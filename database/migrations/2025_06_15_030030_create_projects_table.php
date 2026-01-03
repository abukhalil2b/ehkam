<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Every Year create new projects and related Activities
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cate',20)->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('executor_id')->nullable();
            $table->foreignId('indicator_id')->constrained('indicators')->onDelete('cascade');
            $table->timestamps();
        });
 
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

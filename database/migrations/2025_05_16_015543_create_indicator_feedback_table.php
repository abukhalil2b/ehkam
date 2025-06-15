<?php

use App\Models\User;
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
        Schema::create('indicator_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('target');
            $table->bigInteger('indicator_id');
            $table->bigInteger('year_statement_id');
            $table->bigInteger('sector_id');
        });

        Schema::create('indicator_feedback_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_feedback_id');
            $table->string('to_be_achieve', 12);
            $table->string('achieved', 12)->nullable();
            $table->string('evidence_title',50)->nullable();
            $table->string('evidence_url')->nullable();
            $table->foreignIdFor(User::class,'createdby_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_feedback');

        Schema::dropIfExists('indicator_feedback_values');
    }
};

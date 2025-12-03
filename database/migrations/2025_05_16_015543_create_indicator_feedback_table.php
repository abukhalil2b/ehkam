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
     
        Schema::create('indicator_feedback_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id');
            $table->foreignId('sector_id');
            $table->integer('achieved')->default(0);
            $table->string('evidence_title',50)->nullable();
            $table->string('evidence_url')->nullable();
            $table->string('current_year')->default('2023');
            $table->text('note')->nullable();
            $table->foreignIdFor(User::class,'createdby_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('indicator_feedback_values');
    }
};

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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');

            // classification
            $table->string('type', 50);      // meeting, conference, competition...
            $table->string('bg_color', 7);      // #c1c1c1
            $table->string('program', 100)->nullable(); // الإجازة القرآنية، المؤتمر...

            // optional metadata
            $table->text('notes')->nullable();

            // for filtering/reporting
            $table->year('year')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};

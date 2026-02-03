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
        Schema::table('workshop_attendances', function (Blueprint $table) {
            // Drop the existing globally unique index
            // Laravel default naming convention: table_column_unique
            $table->dropUnique(['attendee_key']);

            // Add the new composite unique index
            $table->unique(['workshop_id', 'attendee_key'], 'workshop_attendee_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_attendances', function (Blueprint $table) {
            // Drop the composite index
            $table->dropUnique('workshop_attendee_unique');

            // Restore the globally unique index
            $table->unique('attendee_key');
        });
    }
};

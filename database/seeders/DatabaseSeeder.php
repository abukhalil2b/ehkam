<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PeriodTemplateSeeder::class);
        // $this->call(SectorSeeder::class);
        // $this->call(IndicatorSeeder::class);
        // $this->call(CalendarEventsSeeder::class);
        $this->call(ComprehensivePermissionSeeder::class);
    }
}

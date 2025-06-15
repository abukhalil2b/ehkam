<?php

namespace Database\Seeders;

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
        $this->call(PeriodTemplateSeeder::class);
        $this->call(SectorSeeder::class);
        $this->call(IndicatorSeeder::class);

        User::create([
            'name' => 'إدارة النظام',
            'email' => 'admin@ehkam.com',
            'password' => Hash::make('admin@ehkam.com'),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with real data from indesign-co.com
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            SectionSeeder::class,  // depends on PageSeeder
            ServiceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}

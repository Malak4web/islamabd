<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Eslam Abdulghani Designs Admin',
                'email' => 'admin@eslamabdulghanidesigns.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        Admin::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Data Entry Member',
                'email' => 'editor@eslamabdulghanidesigns.com',
                'role' => 'data_entry',
                'password' => Hash::make('password'),
            ]
        );
    }
}

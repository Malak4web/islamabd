<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        // 1. Ensure primary admin account exists and has role 'admin'
        $admin = DB::table('admins')
            ->where('email', 'admin@eslamabdulghanidesigns.com')
            ->orWhere('id', 1)
            ->first();

        if ($admin) {
            DB::table('admins')
                ->where('id', $admin->id)
                ->update([
                    'role' => 'admin',
                    'password' => Hash::make('password'),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('admins')->insert([
                'id' => 1,
                'name' => 'Eslam Abdulghani Designs Admin',
                'email' => 'admin@eslamabdulghanidesigns.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Ensure data entry editor account exists and has role 'data_entry'
        $editor = DB::table('admins')
            ->where('email', 'editor@eslamabdulghanidesigns.com')
            ->first();

        if ($editor) {
            DB::table('admins')
                ->where('id', $editor->id)
                ->update([
                    'role' => 'data_entry',
                    'password' => Hash::make('password'),
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('admins')->insert([
                'name' => 'Data Entry Member',
                'email' => 'editor@eslamabdulghanidesigns.com',
                'role' => 'data_entry',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

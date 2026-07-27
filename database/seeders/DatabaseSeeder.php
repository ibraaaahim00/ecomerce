<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ✅ Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // ✅ Regular User للاختبار
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );
    }
}

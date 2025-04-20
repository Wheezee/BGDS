<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        User::create([
            'name' => 'Test Volunteer',
            'email' => 'volunteer@example.com',
            'password' => Hash::make('password'),
            'role' => 'volunteer',
        ]);
    }
}

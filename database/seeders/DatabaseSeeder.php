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
        // Array of users to create
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ],
            [
                'name' => 'Dr. John Doe',
                'email' => 'doctor@example.com',
                'password' => Hash::make('doctor'),
                'role' => 'doctor',
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('staff'),
                'role' => 'staff',
            ],
            [
                'name' => 'Patient User',
                'email' => 'patient@example.com',
                'password' => Hash::make('patient'),
                'role' => 'patient',
            ],
        ];

        // Loop through and create each user
        foreach ($users as $userData) {
            User::factory()->create($userData);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample teacher
        User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '1234567890',
            'email_verified_at' => now(),
        ]);

        // Create sample student
        User::create([
            'name' => 'Jane Student',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU001',
            'phone' => '0987654321',
            'email_verified_at' => now(),
        ]);

        echo "Default users created:\n";
        echo "Admin: admin@school.com / password\n";
        echo "Teacher: teacher@school.com / password\n";
        echo "Student: student@school.com / password\n";
    }
}
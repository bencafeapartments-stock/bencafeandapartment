<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create additional teachers
        $teachers = [
            [
                'name' => 'Alice Mathematics',
                'email' => 'alice@school.com',
                'role' => 'teacher',
                'phone' => '1111111111',
            ],
            [
                'name' => 'Bob Science',
                'email' => 'bob@school.com',
                'role' => 'teacher',
                'phone' => '2222222222',
            ],
            [
                'name' => 'Carol English',
                'email' => 'carol@school.com',
                'role' => 'teacher',
                'phone' => '3333333333',
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create(array_merge($teacher, [
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]));
        }

        // Create additional students
        $students = [
            [
                'name' => 'David Smith',
                'email' => 'david@school.com',
                'student_id' => 'STU002',
                'phone' => '4444444444',
            ],
            [
                'name' => 'Emily Johnson',
                'email' => 'emily@school.com',
                'student_id' => 'STU003',
                'phone' => '5555555555',
            ],
            [
                'name' => 'Frank Williams',
                'email' => 'frank@school.com',
                'student_id' => 'STU004',
                'phone' => '6666666666',
            ],
            [
                'name' => 'Grace Brown',
                'email' => 'grace@school.com',
                'student_id' => 'STU005',
                'phone' => '7777777777',
            ],
        ];

        foreach ($students as $student) {
            User::create(array_merge($student, [
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]));
        }

        // Create sample grades
        $teacherIds = User::where('role', 'teacher')->pluck('id')->toArray();
        $studentIds = User::where('role', 'student')->pluck('id')->toArray();

        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Geography'];
        $semesters = ['Fall 2024', 'Spring 2024', 'Summer 2024'];

        foreach ($studentIds as $studentId) {
            foreach ($subjects as $subject) {
                Grade::create([
                    'student_id' => $studentId,
                    'teacher_id' => $teacherIds[array_rand($teacherIds)],
                    'subject' => $subject,
                    'grade' => rand(70, 100),
                    'semester' => $semesters[array_rand($semesters)],
                    'remarks' => rand(1, 3) == 1 ? 'Good performance' : null,
                ]);
            }
        }

        echo "Sample data created successfully!\n";
    }
}

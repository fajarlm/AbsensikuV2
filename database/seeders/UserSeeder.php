<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Create([
            'name' => 'Admin1',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('admin123')
        ]);
        User::create([
            'name' => 'Teacher1',
            'username' => 'teacher',
            'role' => 'teacher',
            'password' => Hash::make('teacher123')
        ]);
        User::create([
            'name' => 'Student1',
            'username' => 'student',
            'role' => 'student',
            'password' => Hash::make('student123')
        ]);

    }
    
}

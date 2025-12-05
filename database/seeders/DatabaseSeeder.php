<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudyGroup;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([ UserSeeder::class, StudyGroupSeeder::class ]);
        $this->call(UserSeeder::class);
        
        StudyGroup::factory(1)->create();
        Subject::factory(1)->create();
        Student::factory(2)->create();
        Teacher::factory(3)->create();
        Schedule::factory(5)->create();
        Attendance::factory(10)->create();
        
        // Subject::factory()->count(10)->create();
    }
}

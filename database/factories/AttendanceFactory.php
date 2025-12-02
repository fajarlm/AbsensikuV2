<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_id'  => Student::factory(),
            'schedule_id' => Schedule::factory(),
            'attendance_date'        => $this->faker->date(), // atau dateTime kalau mau
            'status'      => $this->faker->randomElement([
                'present',
                'absent',
                'sick',
                'permission',
                'dispensed'
            ]),
            'note'        => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}

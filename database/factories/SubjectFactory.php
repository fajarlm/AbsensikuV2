<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'teacher_id'  => Teacher::factory(),
            'name'        => $this->faker->randomElement([
                'Math',
                'English',
                'Science',
                'History',
                'Religion',
                'Art',
                'Sports'
            ]),
            'code'        => $this->faker->unique()->bothify('SBJ-###'),
            'description' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null,
        ];
    }
}

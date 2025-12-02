<?php

namespace Database\Factories;

use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'user_id'        => User::factory()->state(fn () => ['role' => 'student']),
            'study_group_id' => StudyGroup::factory(),
            'nis'            => $this->faker->unique()->numerify('24###'),
            'nisn'           => $this->faker->unique()->numerify('00########'),
            'first_log'      => false,
            'verification_code' => 'nis',
        ];
    }
}

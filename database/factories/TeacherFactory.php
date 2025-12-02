<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
     return [
            'user_id' => User::factory()->state(fn () => ['role' => 'teacher']),
            'nip'     => $this->faker->unique()->numerify('1980#######'),
            'status'  => $this->faker->randomElement(['active', 'inactive']),
        ];
}

}

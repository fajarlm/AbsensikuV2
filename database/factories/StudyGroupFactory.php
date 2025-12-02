<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyGroup>
 */
class StudyGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'class_number' => $this->faker->randomElement(['1', '2', '3', '4', '5', '6']),
            'grade'        => $this->faker->randomElement(['X', 'XI', 'XII']),
            'major'        => $this->faker->randomElement(['PPLG', 'TJKT', 'DKV', 'PMN', 'MPLB', 'KLN', 'HTL']),
            'major'        => $this->faker->randomElement(['PPLG', 'TJKT', 'DKV', 'PMN', 'MPLB', 'KLN', 'HTL']),
        ];
    }
}

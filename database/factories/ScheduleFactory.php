<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = $this->faker->time('H:i:s');
        $end   = date('H:i:s', strtotime($start) + 3600);

        return [
            'study_group_id' => \App\Models\StudyGroup::factory(),
            'subject_id'     => \App\Models\Subject::factory(),
            'day'            => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
            'start_time'     => $start,
            'end_time'       => $end,
        ];
    }
}

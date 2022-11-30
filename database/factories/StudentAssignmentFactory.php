<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAssignment>
 */
class StudentAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->uuid(),
            'assignment_group_id' => fake()->uuid(),
            'subject_assignment_id' => fake()->uuid(),
            'answer' => fake()->randomLetter(),
        ];
    }
}

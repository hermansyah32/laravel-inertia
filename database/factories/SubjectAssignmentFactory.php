<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectAssignment>
 */
class SubjectAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'assignment_group_id' => fake()->uuid(),
            'type' => 'multiple-choice',
            'question' => 'Question ' . fake()->randomLetter(),
            'options' => json_encode(['A' => 'True', 'B' => 'False', 'C' => 'False', 'D' => 'False', 'E' => 'False',]),
            'answer' => 'A',
            'score' => fake()->randomNumber(),
        ];
    }
}

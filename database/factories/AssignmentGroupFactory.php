<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentGroup>
 */
class AssignmentGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject_group_id' => fake()->randomNumber(),
            'subject_content_id' => fake()->randomNumber(),
            'title' => 'Assignment Group ' . fake()->randomLetter(),
        ];
    }
}

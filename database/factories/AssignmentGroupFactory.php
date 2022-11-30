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
            'subject_id' => fake()->uuid(),
            'subject_group_id' => fake()->uuid(),
            'subject_content_id' => fake()->uuid(),
            'name' => 'Assignment Group ' . fake()->randomLetter(),
        ];
    }
}

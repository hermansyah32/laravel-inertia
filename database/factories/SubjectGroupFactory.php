<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectGroup>
 */
class SubjectGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'SubjectGroup ' . fake()->randomLetter(),
            'subject_id' => fake()->uuid(),
            'order' => 1,
        ];
    }
}

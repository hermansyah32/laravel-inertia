<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectContent>
 */
class SubjectContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => 'Content ' . fake()->randomLetter(),
            'content' => fake()->paragraph(),
            'order' => fake()->randomNumber(),
            'subject_group_id' => fake()->uuid(),
        ];
    }
}

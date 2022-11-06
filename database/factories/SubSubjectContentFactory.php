<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubSubjectContent>
 */
class SubSubjectContentFactory extends Factory
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
            'subject_content_id' => fake()->randomNumber(),
        ];
    }
}

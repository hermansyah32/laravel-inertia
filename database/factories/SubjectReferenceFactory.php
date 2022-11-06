<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubjectReference>
 */
class SubjectReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Content ' . fake()->randomLetter(),
            'url' => fake()->url(),
            'subject_content_id' => fake()->randomNumber(),
        ];
    }
}

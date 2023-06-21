<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'age'=>fake()->numberBetween(20,65),
            'salary' => fake()->randomFloat(2,1200,2500),
            'school_id' => fake()->numberBetween(1,50),
            'image'=>fake()->imageUrl(),

            //
        ];
    }
}

<?php

namespace Database\Factories\Infrastructure\Note;

use App\Infrastructure\Note\NoteModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NoteModel>
 */
class NoteModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'title' => fake()->sentence(3),
            'content' => fake()->paragraph(),
            'created_at' => fake()->dateTimeBetween('-1 year'),
            'updated_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}

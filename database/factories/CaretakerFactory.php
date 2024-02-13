<?php

namespace Database\Factories;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caretaker>
 */
class CaretakerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'memento' => $this->faker->realText,
            'is_current' => rand(0, 1),
            'snapshotable_type' => $this->faker->randomElement([Machine::class]),
            'snapshotable_id' => Machine::factory(),
        ];
    }
}

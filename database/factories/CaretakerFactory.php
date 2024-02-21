<?php

namespace Database\Factories;

use App\Factory\Memento;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory
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

    /**
     * Indicate that the memento is current model situation
     */
    public function current(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_current' => 1
            ];
        });
    }

    /**
     * Indicate new snapshot's model
     */
    public function model(Model $model): Factory
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'snapshotable_type' => $model::class,
                'snapshotable_id' => $model->id,
            ];
        });
    }

    /**
     * Indicate new snapshot's memento
     */
    public function memento(Memento $memento): Factory
    {
        return $this->state(function (array $attributes) use ($memento) {
            return [
                'memento' => $memento,
            ];
        });
    }
}

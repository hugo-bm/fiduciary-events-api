<?php

namespace Database\Factories;

use App\Models\Obligation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Obligation>
 */
class ObligationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operation_id' => \App\Models\Operation::factory(),
            'title' => fake()->sentence(),
            'due_date' => now()->addDays(10),
            'status' => 'PENDING',
        ];
    }
}

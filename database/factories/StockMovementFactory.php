<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['in', 'out']),
            'quantity' => fake()->numberBetween(1, 15),
            'notes' => fake()->sentence(4),
        ];
    }
}

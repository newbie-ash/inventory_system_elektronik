<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => strtoupper('PRD-' . fake()->unique()->bothify('###??')),
            'description' => fake()->sentence(8),
            'price' => fake()->numberBetween(10, 500) * 1000, // e.g. 10.000 - 500.000 IDR
            'stock' => fake()->numberBetween(10, 100),
            'min_stock' => 5,
        ];
    }
}

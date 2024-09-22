<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(), // Názov
            'description' => $this->faker->sentence(10), // Popis
            'price' => $this->faker->randomFloat(2, 1, 100), // Cena medzi 1 a 100
            'category_id' => Category::inRandomOrder()->first()->id, // Vyber náhodnú kategóriu
            'sku' => $this->faker->unique()->word(), // SKU
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']), // Pridanie veľkosti
            'color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White']), // Pridanie farby
        ];
    }
}

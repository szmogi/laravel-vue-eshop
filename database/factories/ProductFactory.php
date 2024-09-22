<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {

        $masters = [ 100020, 100021, 100022, 100023, 100024, 100025, 100026];

        return [
            'name' => $this->faker->word(), // Názov
            'description' => $this->faker->sentence(10), // Popis
            'price' => $this->faker->randomFloat(2, 1, 100), // Cena medzi 1 a 100
            'category_id' => Category::inRandomOrder()->first()->id, // Vyber náhodnú kategóriu
            'sku' => $this->faker->unique()->word(), // SKU
            'size_id' => Size::inRandomOrder()->first()->id, // platná veľkosť
            'color_id' => Color::inRandomOrder()->first()->id, // platná farba
            'master_id' => $masters[array_rand($masters)], // master_id
            'stock' => $this->faker->numberBetween(1, 10), // počet dostupných produktov
        ];
    }
}


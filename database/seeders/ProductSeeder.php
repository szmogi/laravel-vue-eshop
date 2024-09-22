<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Vytvoríme 50 hlavných produktov
        Product::factory()->count(100)->create();
    }
}

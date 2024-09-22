<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];

        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, // Kategórie
            ColorSeeder::class, // Farby
            SizeSeeder::class, // Veľkosti
            ProductSeeder::class, // Produkty
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            ['name' => 'Beef',   'stock_amount' => 20000, 'unit' => 'g'], // 20kg in grams
            ['name' => 'Cheese', 'stock_amount' => 5000,  'unit' => 'g'], // 5kg in grams
            ['name' => 'Onion',  'stock_amount' => 1000,  'unit' => 'g'], // 1kg in grams
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}

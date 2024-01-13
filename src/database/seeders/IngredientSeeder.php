<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            ['name' => 'Beef', 'stock_amount' => config('ingredients.initial_stocks.Beef'), 'unit' => 'g'], // 20kg in grams
            ['name' => 'Cheese', 'stock_amount' => config('ingredients.initial_stocks.Cheese'), 'unit' => 'g'], // 5kg in grams
            ['name' => 'Onion', 'stock_amount' => config('ingredients.initial_stocks.Onion'), 'unit' => 'g'], // 1kg in grams
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => 'Burger',
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Ingredients with predefined quantities
            $ingredients = [
                ['name' => 'Beef', 'amount' => 150],
                ['name' => 'Cheese', 'amount' => 30],
                ['name' => 'Onion', 'amount' => 20]
            ];

            foreach ($ingredients as $ingredientData) {
                $ingredient = Ingredient::where('name', $ingredientData['name'])->first();

                if ($ingredient) {
                    $product->ingredients()->attach($ingredient->id, ['amount' => $ingredientData['amount'], 'unit' => 'g']);
                }
            }
        });
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testOrderCreationWithSufficientStock()
    {
        // Arrange: Create products and ingredients with sufficient stock
        $product = Product::factory()->create();
        $ingredient = Ingredient::factory()->create(['stock_amount' => 100]);
        $product->ingredients()->attach($ingredient->id, ['amount' => 10, 'unit' => 'g']);

        // Act: Make a POST request to the order store route
        $response = $this->postJson('api/orders', [
            'products' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ]);
        if ($response->status() !== 200) {
            dd($response->json()); // This will dump the response content.
        }
        // Assert: Check if the order was stored and stock was updated
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['id' => 1]);
        $this->assertEquals(80, $ingredient->fresh()->stock_amount);
    }

    public function testOrderCreationWithInsufficientStock()
    {
        // Arrange: Create products and ingredients with insufficient stock
        $product = Product::factory()->create();
        $ingredient = Ingredient::factory()->create(['stock_amount' => 5]);
        $product->ingredients()->attach($ingredient->id, ['amount' => 10, 'unit' => 'g']);

        // Act: Make a POST request to the order store route
        $response = $this->postJson('api/orders', [
            'products' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ]);
        // Assert: Check the response for insufficient stock
        $response->assertStatus(422);
        $response->assertJson(['message' => 'Failed to process the order due to insufficient stock']);
    }

    public function testOrderCreationWithInvalidProductId()
    {
        // Arrange: Create a product and get an invalid product ID
        $validProduct = Product::factory()->create();
        $invalidProductId = $validProduct->id + 1; // Assuming this ID is invalid

        // Act: Make a POST request to the order store route with the invalid product ID
        $response = $this->postJson('/api/orders', [
            'products' => [
                ['product_id' => $invalidProductId, 'quantity' => 1]
            ]
        ]);

        // Assert: Check the response for an error due to the invalid product ID
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The selected products.0.product_id is invalid.'
        ]);
    }

}

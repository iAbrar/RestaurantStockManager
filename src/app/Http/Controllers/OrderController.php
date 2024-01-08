<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create a new order
            $order = new Order();
            // Assuming setting other necessary fields like total, status, etc.
            $order->save(); // Save the order to generate an ID

            // Check if all ingredients are available in sufficient quantity
            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['product_id']);

                foreach ($product->ingredients as $ingredient) {
                    $totalAmountNeeded = $productData['quantity'] * $ingredient->pivot->amount;

                    if ($ingredient->stock_amount < $totalAmountNeeded) {
                        throw new \Exception('Insufficient stock for ingredient: ' . $ingredient->name . '. Required: ' . $totalAmountNeeded . ', Available: ' . $ingredient->stock_amount);
                    }
                }
            }

            // If checks pass, process the order
            foreach ($validated['products'] as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);

                foreach ($product->ingredients as $ingredient) {
                    $totalAmountUsed = $productData['quantity'] * $ingredient->pivot->amount;
                    $ingredient->reduceStock($totalAmountUsed);
                }
            }

            // Set the order status and save again
            $order->markAsCompleted(); // Assuming this method sets the status
            $order->save(); // Save the order again to update the status

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Order processed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            return response()->json([
                'message' => 'Failed to process the order due to insufficient stock',
                'error' => $e->getMessage()
            ], 422);
        }
    }


}

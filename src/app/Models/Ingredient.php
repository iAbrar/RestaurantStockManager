<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'stock_quantity', // Stored in grams
        'unit'            // Unit for this ingredient (e.g., 'g', 'kg')
    ];

    /**
     * The products that use the ingredient.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Accessor to get the stock quantity in the specified unit.
     */
    public function getFormattedQuantityAttribute()
    {
        if ($this->unit === 'kg') {
            return ($this->stock_quantity / 1000) . ' kg'; // Convert to kilograms
        }
        return $this->stock_quantity . ' g'; // Default is grams
    }

    /**
     * Check if the ingredient is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Reduce the stock quantity of the ingredient.
     *
     * @param  int  $quantity
     * @return void
     */
    public function reduceStock($quantity)
    {
        $this->decrement('stock_quantity', $quantity);
    }

    /**
     * Increase the stock quantity of the ingredient.
     *
     * @param  int  $quantity
     * @return void
     */
    public function increaseStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
    }

}

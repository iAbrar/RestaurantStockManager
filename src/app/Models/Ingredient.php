<?php

namespace App\Models;

use Decimal\Decimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    const UNIT_GRAMS = 'g';
    const UNIT_MILLILITERS = 'ml';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'stock_amount',
        'unit'            // Unit for this ingredient (e.g., 'g', 'ml')
    ];

    /**
     * The products that use the ingredient.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'ingredient_product')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }


    /**
     * Accessor to get the stock amount in the specified unit.
     */
    public function getFormattedAmountAttribute()
    {
        if ($this->unit === 'kg') {
            return ($this->stock_amount / 1000) . ' kg'; // Convert to kilograms
        }
        return $this->stock_amount . ' g'; // Default is grams
    }

    /**
     * Check if the ingredient is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->stock_amount > 0;
    }

    /**
     * Reduce the stock quantity of the ingredient.
     *
     * @param decimal $amount
     * @return void
     */
    public function reduceStock($amount)
    {
        $this->decrement('stock_amount', $amount);
    }

    /**
     * Increase the stock $amount of the ingredient.
     *
     * @param decimal $amount
     * @return void
     */
    public function increaseStock($amount)
    {
        $this->increment('stock_amount', $amount);
    }

}

<?php

namespace App\Models;

use App\Notifications\LowStockNotification;
use Decimal\Decimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Log;

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
        'unit',            // Unit for this ingredient (e.g., 'g', 'ml')
        'is_notification_sent'
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
    public function reduceStock($quantityUsed)
    {
        $this->stock_amount -= $quantityUsed;

        $initialStock = config("ingredients.initial_stocks." . $this->name);
        Log::info('Reducing stock', ['ingredient' => $this->name, 'new_stock_amount' => $this->stock_amount]);

        if ($initialStock && $this->stock_amount < ($initialStock / 2) && !$this->is_notification_sent) {
            $stockAlertEmail = env('STOCK_ALERT_EMAIL', 'default@example.com');
            Log::info('Low stock, sending notification', ['ingredient' => $this->name]);

            // Notify via the specified email
            NotificationFacade::route('mail', $stockAlertEmail)
                ->notify(new LowStockNotification($this));

            $this->is_notification_sent = true;

        }


        $this->save();
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

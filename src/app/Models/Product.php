<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'quantity',
    ];

    /**
     * The ingredients that belong to the product.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_product')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

    /**
     * The orders that contain the product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

}

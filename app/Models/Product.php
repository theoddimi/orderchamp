<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    public function carts() 
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_id', 'cart_id')
                ->withPivot('quantity', 'completed');
    }

}

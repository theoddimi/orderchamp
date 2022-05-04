<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $table = 'cart';
    
    protected $fillable = [
        'user_id',
    ];
    
    public $totalQuantity = null;
    public $totalAmount = 0;
    
    private function refreshTotalQuantityAndAmount()
    {
        $this->totalQuantity = 0;
        
        foreach($this->activeWithProducts as $product) {
           $this->totalQuantity += $product->pivot->quantity;
           $this->totalAmount +=  $product->pivot->quantity * $product->price;
        }
    }
    
    public function getTotalQuantity()
    {
        if (null === $this->totalQuantity) {
            $this->refreshTotalQuantityAndAmount();
        }
        
        return $this->totalQuantity;
    }
    
    public function getTotalAmount()
    {
        $this->totalAmount = 0;
        
        $this->refreshTotalQuantityAndAmount();
       
        return $this->totalAmount;
    }
    
    /**
     * Relations
     * @return type
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id' , 'product_id')
                ->withPivot('quantity', 'completed');
    }
    
    public function activeWithProducts()
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id' , 'product_id')
                ->withPivot('quantity', 'completed')->wherePivot('completed', 0);
    }
}

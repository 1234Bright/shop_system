<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'size_id',
        'product_code',
        'color',
    ];
    
    /**
     * Get the product that owns this detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get the size that belongs to this detail.
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    
    /**
     * Auto-generate a product code when creating a new product detail
     * by combining the product code with a unique identifier for the detail
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($productDetail) {
            // Get the parent product
            $product = Product::find($productDetail->product_id);
            if ($product) {
                // Generate a unique code based on the product code and a random suffix
                $productDetail->product_code = $product->product_code . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }
}

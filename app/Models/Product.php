<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'product_code',
        'category_id',
        'brand_id',
        'image',
        'description',
        'status',
        'price',
        'cost',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate product code before creating
        static::creating(function ($product) {
            $product->product_code = 'PRD-' . strtoupper(substr(uniqid(), -8));
        });
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    /**
     * Get the details for this product.
     */
    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_detail_id',
        'quantity',
        'price',
        'amount',
        'subtotal',
    ];
    
    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }
    
    // Calculate subtotal before saving
    protected static function booted()
    {
        static::creating(function ($detail) {
            $detail->subtotal = $detail->price * $detail->quantity;
        });
        
        static::updating(function ($detail) {
            $detail->subtotal = $detail->price * $detail->quantity;
        });
    }
}

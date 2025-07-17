<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'order_date',
        'total_amount',
        'payment_method',
        'status',
        'currency_id',
        'notes',
    ];
    
    protected $casts = [
        'order_date' => 'date',
    ];
    
    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    // Generate a unique invoice number (format: INV-YYMM-XXXXX)
    public static function generateInvoiceNumber()
    {
        $latestInvoice = self::orderBy('created_at', 'desc')->first();
        
        $yearMonth = now()->format('ym');
        
        if (!$latestInvoice) {
            return 'INV-' . $yearMonth . '-00001';
        }
        
        $lastNumber = $latestInvoice->invoice_number;
        
        if (strpos($lastNumber, $yearMonth) !== false) {
            $sequence = intval(substr($lastNumber, -5)) + 1;
        } else {
            $sequence = 1;
        }
        
        return 'INV-' . $yearMonth . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
}

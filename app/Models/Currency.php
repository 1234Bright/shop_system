<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'symbol',
        'is_default'
    ];
    
    protected $casts = [
        'is_default' => 'boolean',
    ];
}

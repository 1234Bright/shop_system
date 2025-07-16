<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add default USD currency
        Currency::create([
            'name' => 'US Dollar',
            'symbol' => '$',
            'is_default' => true
        ]);
        
        // Add some other common currencies
        $currencies = [
            [
                'name' => 'Euro',
                'symbol' => '€',
                'is_default' => false
            ],
            [
                'name' => 'British Pound',
                'symbol' => '£',
                'is_default' => false
            ],
        ];
        
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}

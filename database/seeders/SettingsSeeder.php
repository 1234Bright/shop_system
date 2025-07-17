<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company information
            [
                'key' => 'company_name',
                'value' => 'My Shop System',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Name'
            ],
            [
                'key' => 'company_address',
                'value' => '123 Business Street, City, Country',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Address'
            ],
            [
                'key' => 'company_phone',
                'value' => '+123456789',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Phone'
            ],
            [
                'key' => 'company_email',
                'value' => 'info@myshopsystem.com',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Email'
            ],
            [
                'key' => 'company_logo',
                'value' => null,
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Logo Path'
            ],
            [
                'key' => 'invoice_footer_text',
                'value' => 'Thank you for your business!',
                'type' => 'text',
                'group' => 'invoice',
                'label' => 'Invoice Footer Text'
            ],
            [
                'key' => 'tax_rate',
                'value' => '0',
                'type' => 'float',
                'group' => 'invoice',
                'label' => 'Tax Rate (%)'
            ],
            [
                'key' => 'show_tax_on_invoice',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'invoice',
                'label' => 'Show Tax on Invoice'
            ],
        ];
        
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

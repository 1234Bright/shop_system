<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Show the system settings form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all currencies for the dropdown
        $currencies = Currency::all();
        
        // Get current default currency
        $defaultCurrency = Currency::where('is_default', true)->first();
        
        // Get all settings grouped by category
        $companySettings = Setting::where('group', 'company')->get();
        $invoiceSettings = Setting::where('group', 'invoice')->get();
        
        return view('admin.settings.index', compact('currencies', 'defaultCurrency', 'companySettings', 'invoiceSettings'));
    }
    
    /**
     * Update system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_currency_id' => 'required|exists:currencies,id',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'invoice_footer_text' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'show_tax_on_invoice' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('admin.settings.index')
                ->withErrors($validator)
                ->withInput();
        }
        
        // First reset all currencies to non-default
        Currency::where('is_default', true)->update(['is_default' => false]);
        
        // Then set the selected one as default
        $currency = Currency::find($request->default_currency_id);
        $currency->is_default = true;
        $currency->save();
        
        // Update company settings
        Setting::set('company_name', $request->company_name, 'text', 'company', 'Company Name');
        Setting::set('company_address', $request->company_address, 'text', 'company', 'Company Address');
        Setting::set('company_phone', $request->company_phone, 'text', 'company', 'Company Phone');
        Setting::set('company_email', $request->company_email, 'text', 'company', 'Company Email');
        
        // Handle company logo upload
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            // Store new logo
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            Setting::set('company_logo', $logoPath, 'text', 'company', 'Company Logo Path');
        }
        
        // Update invoice settings
        Setting::set('invoice_footer_text', $request->invoice_footer_text, 'text', 'invoice', 'Invoice Footer Text');
        Setting::set('tax_rate', $request->tax_rate ?? '0', 'float', 'invoice', 'Tax Rate (%)');
        Setting::set('show_tax_on_invoice', $request->has('show_tax_on_invoice') ? '1' : '0', 'boolean', 'invoice', 'Show Tax on Invoice');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'System settings updated successfully!');
    }
}

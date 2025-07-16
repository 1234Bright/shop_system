<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
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
        
        return view('admin.settings.index', compact('currencies', 'defaultCurrency'));
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
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'System settings updated successfully!');
    }
}

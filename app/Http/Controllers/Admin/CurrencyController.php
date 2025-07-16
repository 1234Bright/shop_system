<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:currencies',
            'symbol' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);
        
        // Set is_default to false if not provided
        if (!isset($validated['is_default'])) {
            $validated['is_default'] = false;
        }
        
        // If this currency is set as default, unset all other currencies as default
        if ($validated['is_default']) {
            DB::table('currencies')->update(['is_default' => false]);
        }
        
        Currency::create($validated);
        
        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currency = Currency::findOrFail($id);
        return view('admin.currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $currency = Currency::findOrFail($id);
        return view('admin.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $currency = Currency::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $id,
            'symbol' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);
        
        // Set is_default to false if not provided
        if (!isset($validated['is_default'])) {
            $validated['is_default'] = false;
        }
        
        // If this currency is set as default, unset all other currencies as default
        if ($validated['is_default']) {
            DB::table('currencies')->where('id', '!=', $id)->update(['is_default' => false]);
        }
        
        $currency->update($validated);
        
        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currency = Currency::findOrFail($id);
        
        // Prevent deletion of default currency
        if ($currency->is_default) {
            return redirect()->route('admin.currencies.index')
                ->with('error', 'Cannot delete the default currency!');
        }
        
        $currency->delete();
        
        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency deleted successfully!');
    }
    
    /**
     * Set a currency as default
     */
    public function setDefault(string $id)
    {
        $currency = Currency::findOrFail($id);
        
        // Unset all currencies as default
        DB::table('currencies')->update(['is_default' => false]);
        
        // Set this currency as default
        $currency->update(['is_default' => true]);
        
        return redirect()->route('admin.currencies.index')
            ->with('success', 'Default currency updated successfully!');
    }
}

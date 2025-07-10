<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used as we're using modal
        return redirect()->route('admin.customers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('customers', 'public');
            $validated['picture'] = $picturePath;
        }
        
        Customer::create($validated);
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used as we're displaying all customers in index
        return redirect()->route('admin.customers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($customer->picture && Storage::disk('public')->exists($customer->picture)) {
                Storage::disk('public')->delete($customer->picture);
            }
            
            $picturePath = $request->file('picture')->store('customers', 'public');
            $validated['picture'] = $picturePath;
        }
        
        // Handle picture removal
        if ($request->has('remove_picture') && $request->remove_picture == 1) {
            if ($customer->picture && Storage::disk('public')->exists($customer->picture)) {
                Storage::disk('public')->delete($customer->picture);
            }
            $validated['picture'] = null;
        }
        
        $customer->update($validated);
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        
        // Delete customer picture if exists
        if ($customer->picture && Storage::disk('public')->exists($customer->picture)) {
            Storage::disk('public')->delete($customer->picture);
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used as we're using modal
        return redirect()->route('admin.suppliers.index');
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
            $picturePath = $request->file('picture')->store('suppliers', 'public');
            $validated['picture'] = $picturePath;
        }
        
        Supplier::create($validated);
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used as we're displaying all suppliers in index
        return redirect()->route('admin.suppliers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($supplier->picture && Storage::disk('public')->exists($supplier->picture)) {
                Storage::disk('public')->delete($supplier->picture);
            }
            
            $picturePath = $request->file('picture')->store('suppliers', 'public');
            $validated['picture'] = $picturePath;
        }
        
        // Handle picture removal
        if ($request->has('remove_picture') && $request->remove_picture == 1) {
            if ($supplier->picture && Storage::disk('public')->exists($supplier->picture)) {
                Storage::disk('public')->delete($supplier->picture);
            }
            $validated['picture'] = null;
        }
        
        $supplier->update($validated);
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Delete supplier picture if exists
        if ($supplier->picture && Storage::disk('public')->exists($supplier->picture)) {
            Storage::disk('public')->delete($supplier->picture);
        }
        
        $supplier->delete();
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully!');
    }
}

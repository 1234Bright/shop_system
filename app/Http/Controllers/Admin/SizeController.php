<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used as we're using modal
        return redirect()->route('admin.sizes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes'
        ]);
        
        Size::create($validated);
        
        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used as we're displaying all sizes in index
        return redirect()->route('admin.sizes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $size = Size::findOrFail($id);
        return response()->json($size);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $size = Size::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $id
        ]);
        
        $size->update($validated);
        
        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        
        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Size;
use Illuminate\Support\Facades\Validator;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of product details for a specific product.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function index($productId)
    {
        $product = Product::with('details.size')->findOrFail($productId);
        $totalQuantity = $product->details->sum('quantity');
        return view('admin.products.details.index', compact('product', 'totalQuantity'));
    }

    /**
     * Show the form for creating a new product detail.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $sizes = Size::all();
        return view('admin.products.details.create', compact('product', 'sizes'));
    }

    /**
     * Store a newly created product detail in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'size_id' => 'required|exists:sizes,id',
            'color' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($productId);
        
        // Create product detail with auto-generated product code
        $productDetail = new ProductDetail($request->all());
        $productDetail->product_id = $productId;
        $productDetail->save();

        return redirect()->route('admin.products.details.index', $productId)
            ->with('success', 'Product detail added successfully.');
    }

    /**
     * Show the form for editing the specified product detail.
     *
     * @param  int  $productId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $productDetail = ProductDetail::findOrFail($id);
        $sizes = Size::all();
        
        return view('admin.products.details.edit', compact('product', 'productDetail', 'sizes'));
    }

    /**
     * Update the specified product detail in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'size_id' => 'required|exists:sizes,id',
            'color' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $productDetail = ProductDetail::findOrFail($id);
        $productDetail->update($request->all());

        return redirect()->route('admin.products.details.index', $productId)
            ->with('success', 'Product detail updated successfully.');
    }

    /**
     * Remove the specified product detail from storage.
     *
     * @param  int  $productId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId, $id)
    {
        $productDetail = ProductDetail::findOrFail($id);
        $productDetail->delete();

        return redirect()->route('admin.products.details.index', $productId)
            ->with('success', 'Product detail deleted successfully.');
    }
}

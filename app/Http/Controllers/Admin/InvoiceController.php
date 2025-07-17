<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'currency'])->latest()->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('details')->where('quantity', '>', 0)->where('status', 'active')->get();
        $defaultCurrency = Currency::where('is_default', true)->first();
        $currencies = Currency::all();
        $invoiceNumber = Invoice::generateInvoiceNumber();
        
        return view('admin.invoices.create', compact('customers', 'products', 'defaultCurrency', 'currencies', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'customer_id' => 'nullable|exists:customers,id',
            'order_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,cheque',
            'currency_id' => 'required|exists:currencies,id',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_detail_id' => 'nullable|exists:product_details,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Calculate total amount from products
            $totalAmount = 0;
            foreach ($request->products as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $validated['invoice_number'],
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'currency_id' => $validated['currency_id'],
                'notes' => $validated['notes'],
                'status' => 'paid', // Since this is a sales page, we assume paid on creation
            ]);
            
            // Create invoice details
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check if enough quantity is available
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Not enough inventory for {$product->name}. Available: {$product->quantity}");
                }
                
                // Check if this is a product variant (detail)
                $productDetailId = isset($item['product_detail_id']) && !empty($item['product_detail_id']) ? 
                    $item['product_detail_id'] : null;
                    
                // Create detail record
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'product_detail_id' => $productDetailId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'amount' => $item['price'] * $item['quantity'],
                ]);
                
                // Handle inventory update
                if ($productDetailId) {
                    // This is a variant - update both main product and variant quantities
                    $productDetail = ProductDetail::findOrFail($productDetailId);
                    
                    // Check if enough variant quantity is available
                    if ($productDetail->quantity < $item['quantity']) {
                        throw new \Exception("Not enough inventory for variant {$product->name} ({$productDetail->product_code}). Available: {$productDetail->quantity}");
                    }
                    
                    // Reduce both product and productDetail quantities
                    $productDetail->quantity -= $item['quantity'];
                    $productDetail->save();
                    
                    $product->quantity -= $item['quantity'];
                    $product->save();
                } else {
                    // This is a main product without variants
                    $product->quantity -= $item['quantity'];
                    $product->save();
                }
            }
            
            DB::commit();
            return redirect()->route('admin.invoices.index')
                ->with('success', 'Sale completed successfully! Invoice #' . $invoice->invoice_number);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error creating invoice: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['customer', 'details.product', 'currency'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Print the specified invoice.
     */
    public function printInvoice(string $id)
    {
        $invoice = Invoice::with(['customer', 'details.product', 'currency'])->findOrFail($id);
        $settings = \App\Models\Setting::getAllSettings();
        return view('admin.invoices.print', compact('invoice', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Invoices should not be editable after creation since inventory has been adjusted
        // Redirecting to show view instead
        return redirect()->route('admin.invoices.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // For this system, we'll simply cancel the invoice rather than delete it
        $invoice = Invoice::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Only allow cancellation if invoice is not already cancelled
            if ($invoice->status === 'cancelled') {
                return back()->withErrors(['error' => 'Invoice is already cancelled.']);
            }
            
            // Restore product quantities
            foreach ($invoice->details as $detail) {
                $product = $detail->product;
                
                // If this was a variant, restore both product and variant quantities
                if ($detail->product_detail_id) {
                    $productDetail = ProductDetail::findOrFail($detail->product_detail_id);
                    $productDetail->quantity += $detail->quantity;
                    $productDetail->save();
                }
                
                // Always restore main product quantity
                $product->quantity += $detail->quantity;
                $product->save();
            }
            
            // Mark invoice as cancelled
            $invoice->status = 'cancelled';
            $invoice->save();
            
            DB::commit();
            return redirect()->route('admin.invoices.index')
                ->with('success', 'Invoice #' . $invoice->invoice_number . ' has been cancelled');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error cancelling invoice: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Get product information via AJAX
     */
    public function getProductInfo(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'available_quantity' => $product->quantity
            ]
        ]);
    }

    /**
     * Get product variants via AJAX
     */
    public function getProductVariants(Request $request, $productId)
    {
        $product = Product::with(['details.size'])->findOrFail($productId);
        
        $variants = $product->details->map(function($detail) {
            return [
                'id' => $detail->id,
                'product_code' => $detail->product_code,
                'size' => $detail->size ? $detail->size->name : 'N/A',
                'color' => $detail->color ?? 'N/A',
                'price' => $detail->price,
                'quantity' => $detail->quantity
            ];
        });
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image ?? 'products/no-image.jpg'
            ],
            'variants' => $variants
        ]);
    }
}

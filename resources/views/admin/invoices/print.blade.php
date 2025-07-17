<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        
        .invoice-header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .invoice-details {
            margin-bottom: 30px;
        }
        
        .invoice-details-table {
            width: 100%;
        }
        
        .invoice-details-table td {
            padding: 5px 0;
        }
        
        .invoice-table th {
            background-color: #f5f5f5;
        }
        
        .invoice-total {
            text-align: right;
            font-weight: bold;
        }
        
        .invoice-footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                margin: 0.5cm;
            }
            
            .container {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="no-print text-end mb-4">
            <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
            <button onclick="window.close()" class="btn btn-secondary ms-2">Close</button>
        </div>
        
        <div class="invoice-container">
            <div class="invoice-header">
                <div class="logo-container">
                    @if(isset($settings['company_logo']) && $settings['company_logo'])
                        <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Company Logo" height="80">
                    @endif
                    <h2>{{ $settings['company_name'] ?? 'Company Name' }}</h2>
                    <p>{{ $settings['company_address'] ?? '' }}</p>
                    @if(isset($settings['company_phone']) && $settings['company_phone'])
                        <p>Phone: {{ $settings['company_phone'] }}</p>
                    @endif
                    @if(isset($settings['company_email']) && $settings['company_email'])
                        <p>Email: {{ $settings['company_email'] }}</p>
                    @endif
                </div>
                
                <div class="invoice-title">
                    INVOICE #{{ $invoice->invoice_number }}
                </div>
            </div>
            
            <div class="row invoice-details">
                <div class="col-md-6">
                    <h5>Bill To:</h5>
                    @if($invoice->customer)
                        <p>
                            <strong>{{ $invoice->customer->name }}</strong><br>
                            {{ $invoice->customer->address }}<br>
                            @if($invoice->customer->phone1)
                                Phone: {{ $invoice->customer->phone1 }}<br>
                            @endif
                        </p>
                    @else
                        <p>Walk-in Customer</p>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <table class="invoice-details-table">
                        <tr>
                            <td><strong>Invoice Date:</strong></td>
                            <td>{{ $invoice->order_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td>{{ ucfirst($invoice->payment_method) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>{{ ucfirst($invoice->status) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="invoice-items">
                <table class="table table-bordered invoice-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Item Description</th>
                            <th width="20%">Variant</th>
                            <th width="15%" class="text-end">Unit Price</th>
                            <th width="10%" class="text-center">Quantity</th>
                            <th width="20%" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->product->name }}</td>
                                <td>
                                    @if($detail->productDetail)
                                        <span class="badge bg-light text-dark">{{ $detail->productDetail->product_code }}</span>
                                        <small>
                                            {{ $detail->productDetail->size ? $detail->productDetail->size->name : '' }}
                                            {{ $detail->productDetail->size && $detail->productDetail->color ? ',' : '' }}
                                            {{ $detail->productDetail->color ?? '' }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $invoice->currency->symbol }} {{ number_format($detail->price, 2) }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">{{ $invoice->currency->symbol }} {{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="invoice-total">Total Amount:</td>
                            <td class="text-end invoice-total">{{ $invoice->currency->symbol }} {{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            @if($invoice->notes)
                <div class="invoice-notes mt-4">
                    <h5>Notes:</h5>
                    <p>{{ $invoice->notes }}</p>
                </div>
            @endif
            
            <div class="invoice-footer">
                <p>Thank you for your business!</p>
                <p>{{ $settings['company_name'] ?? 'Company Name' }} &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            // Auto-print when the page loads (optional - uncomment if needed)
            // window.print();
        };
    </script>
</body>
</html>

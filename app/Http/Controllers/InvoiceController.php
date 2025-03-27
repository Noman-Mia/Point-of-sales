<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceProduct;

class InvoiceController extends Controller
{
    public function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
    
            $data = [
                'user_id' => $user_id,
                'customer_id' => $request->customer_id,
                'total' => $request->total,
                'vat' => $request->vat,
                'payable' => $request->payable,
                'discount' => $request->discount,
            ];
            $invoice = Invoice::create($data);
    
            // Products from request body
            $products = $request->products; 
    
            foreach ($products as $product) {
                $existUnit = Product::where('id', $product['id'])->first();
    
                if (!$existUnit) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Product with ID {$product['id']} not found"
                    ], 400);
                }
    
                if ($existUnit->unit < $product['unit']) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Only {$existUnit->unit} units are available in stock for product ID {$product['id']}"
                    ], 400);
                }
    
                // Insert into InvoiceProduct table
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['id'],
                    'user_id' => $user_id,
                    'qty' => $product['unit'],
                    'sale_price' => $product['price'], // Fixed the column name
                ]);
    
                // Update product stock
                Product::where('id', $product['id'])->update([
                    'unit' => $existUnit->unit - $product['unit']
                ]);
            }
    
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage() // Debugging purpose
            ], 500);
        }
    }
    

    public function InvoiceList(Request $request)
    {
        $user_id = $request->header('id');
        $invoices = Invoice::with( 'customer')
        ->where('user_id', $user_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $invoices
        ]);
    }

    public function InvoiceDetails(Request $request)
{
    try {
        $user_id = $request->header('id');

        // Customer Details
        $customerDetails = Customer::where('id', $request->customer_id)->first();
        if (!$customerDetails) {
            return response()->json(['status' => 'failed', 'message' => 'Customer not found'], 404);
        }

        // Invoice Details
        $invoiceDetails = Invoice::where('user_id', $user_id)
            ->where('id', $request->invoice_id)->first();
        if (!$invoiceDetails) {
            return response()->json(['status' => 'failed', 'message' => 'Invoice not found'], 404);
        }

        // Invoice Products
        $invoiceProducts = InvoiceProduct::where('invoice_id', $request->invoice_id)
            ->where('user_id', $user_id)->with('product')->get();

        return response()->json([
            'status' => 'success',
            'Customer' => $customerDetails,
            'Invoice' => $invoiceDetails,
            'Products' => $invoiceProducts
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function InvoiceDelete(Request $request, $invoice_id)
{
    DB::beginTransaction();
    try {
        $user_id = $request->header('id');

        // Check if the invoice exists
        $invoice = Invoice::where('user_id', $user_id)->where('id', $invoice_id)->first();
        if (!$invoice) {
            return response()->json([
                'status' => 'failed',
                'message' => "Invoice with ID $invoice_id not found"
            ], 404);
        }

        // Find and delete all invoice products
        $invoiceProducts = InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->get();

        foreach ($invoiceProducts as $invoiceProduct) {
            // Restore product stock
            Product::where('id', $invoiceProduct->product_id)->increment('unit', $invoiceProduct->qty);

            // Delete invoice product entry
            $invoiceProduct->delete();
        }

        // Delete the invoice
        $invoice->delete();

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => "Invoice and related products deleted successfully"
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'failed',
            'message' => $e->getMessage()
        ], 500);
    }
}


}

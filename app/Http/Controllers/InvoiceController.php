<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceProduct;

class InvoiceController extends Controller
{
    public function InvoiceCreate(Request $request){    
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');

            $data =[
                'user_id' => $user_id, 
                'customer_id' => $request->customer_id,
                'total'=> $request->total,
                'vat'=> $request->vat,
                'payable'=> $request->payable,
                'discount'=> $request->discount,
            ];
            $invoice = Invoice::create($data);

            $products = $request->header('products');

            foreach($products as $product){
                $existUnit = Product::where('id',$product['id'])->first();
                if(!$existUnit){
                    return response()->json([
                        'status'=>'failed',
                        'message'=>"Product with id {$product['id']} not found"
                    ],);
                }
                if($existUnit->unit < $product['unit']){
                    return response()->json([
                        'status'=>'failed',
                        'message'=>"Product with id {$product['id']} has only {$existUnit->unit} unit"
                    ],); 
                }
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['id'],
                    'user_id' => $user_id,
                    'qty' => $product['unit'],
                    'sale)price' => $product['price'],
                ]);
                Product::where('id',$product['id'])->update([
                    'unit' => $existUnit->unit - $product['unit']
                ]);
            }
            DB::commit();
            return response()->json([
                'status'=>'success',
                'message'=>'Invoice created successfully'
            ]);
           
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status'=>'failed',
                'message'=>$e->getMessage()
            ]);
        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function createProduct(Request $request){
        $user_id = $request->header('id');

        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'unit'=>'required',
            'category_id'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data =[
            'user_id'=>$user_id,
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'price'=>$request->price,
            'unit'=>$request->unit,
        ];

        if($request->hasFile('image')){
            $image = $request->file('image');
            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/'.$fileName;
            $image->move(public_path('uploads'),$fileName);
            $data['image'] = $filePath;

        }
        Product::create($data);
        return response()->json([
            'status'=>'success',
            'message'=>'Product created successfully']);
        
    }

    public function ProductList(Request  $request){
        $user_id = $request->header('id');
        $product = Product::where('user_id',$user_id)->get();
        return $product;
    }

    public function ProductById(Request $request){
        $user_id = $request->header('id');
        $product = Product::where('user_id',$user_id)
        ->where('id',$request->id)->first();
        return $product;
    }

    public function ProductUpdate(Request $request)
{
    $user_id = $request->header('id');
    
    // Validate request
    $request->validate([
        'name' => 'required',
        'price' => 'required',
        'unit' => 'required',
        'category_id' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $product = Product::where('user_id', $user_id)->where('id', $request->id)->first();

    if (!$product) {
        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }

    $data = [
        'user_id' => $user_id,
        'category_id' => $request->category_id,
        'name' => $request->name,
        'price' => $request->price,
        'unit' => $request->unit,
    ];

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = time() . '.' . $image->getClientOriginalExtension();
        $filePath = 'uploads/' . $fileName;
        $image->move(public_path('uploads'), $fileName);
        $data['image'] = $filePath;
    }

    $product->update($data);

    return response()->json([
        'status' => 'success',
        'message' => 'Product updated successfully',
        'product' => $product
    ]);
}

public function ProductDelete($id)
{
    try {
        
        $product = Product::findOrFail($id);

        if ($product->image && file_exists(public_path('uploads/' . basename($product->image)))) {
            unlink(public_path(($product->image)));
        }
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => $e->getMessage()
        ], 500);
    }
}



}

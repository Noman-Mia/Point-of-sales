<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

    public function ProductUpdate(Request $request){
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
        Product::where('user_id',$user_id)->where('id',$request->id)->update($data);
        return response()->json([
            'status'=>'success',
            'message'=>'Product updated successfully']);
    }
}
